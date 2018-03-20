<?php

namespace Printi\NotifyBundle;

use ApiClient\Clients\AdminApi;
use Printi\AwsBundle\Services\Sqs\Sqs;
use Printi\NotifyBundle\Exception\NotifyException;
use Psr\Log\InvalidArgumentException;

/**
 * Class Notify
 * @package Printi\NotifyBundle
 */
class Notify extends BaseNotify
{
    const TRANSITION = 'transition';

    const PRIORITY_METHOD = [
        'alpha' => [
            'low'  => 'rest',
            'high' => 'aws',
        ],
    ];

    public function __construct(array $config, Sqs $sqs)
    {
        parent::__construct($config, $sqs);
    }

    public function sendMessage(string $to, array $payload)
    {
        if (!isset($payload['data']) || !isset($payload['type'])) {
            throw new InvalidArgumentException();
        }

        try {

            switch ($payload['type']) {
                case "stateTransition":
                    if (!isset($payload['data']['transition'])) {
                        throw new InvalidArgumentException();
                    }
                    $priority = $this->getNotificationPriority(self::TRANSITION, $payload['data']['transition']);
                    $methodMask = "%sNotify";
                    $method     = sprintf($methodMask, ucfirst(self::PRIORITY_METHOD[$to][$priority]));

                    $this->{$method}($to, $payload);
                    break;
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $to   The service name
     * @param array  $body The message body
     *
     * @throws \Exception
     */
    public function restNotify(string $to, array $body)
    {
        try {

            switch ($to) {
                case 'alpha':
                    AdminApi::getInstance()->sendNotification($body);
                    break;
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $to
     * @param array  $body
     *
     * @throws \Exception
     */
    public function awsNotify(string $to, array $body)
    {
        try {
            switch ($to) {
                case 'alpha':
                    $this->sqs->send('alpha_message', $body);
                    break;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Notify priority based on type(transition) and key(new_upload|prepress_reject)
     *
     * @param string $type The priority type
     * @param string $key  The action key
     *
     * @return mixed
     * @throws NotifyException
     */
    public function getNotificationPriority(string $type, string $key)
    {
        if (!$type || !$key) {
            throw new NotifyException(NotifyException::TYPE_NOTIFY_INVALID_ARGUMENTS);
        }

        if (empty($this->config) || !isset($this->config[$type]) || !isset($this->config[$type][$key])) {
            throw new NotifyException(NotifyException::TYPE_NOTIFY_PRIORITY_NOT_FOUND);
        }

        return $this->config[$type][$key];
    }
}
