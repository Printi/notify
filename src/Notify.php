<?php

namespace Printi\NotifyBundle;

use ApiClient\Clients\AdminApi;
use App\Services\Aws\OmegaSnsClient;
use Printi\NotifyBundle\Exception\NotifyException;

/**
 * Class Notify
 * @package Printi\NotifyBundle
 */
class Notify extends BaseNotify
{
    const TRANSITION = 'transition';

    const PRIORITY_METHOD = [
        'low'  => 'Alpha',
        'high' => 'Aws',
    ];

    const ACTION_METHOD = [
        self::TRANSITION => 'Transition',
    ];

    const SNS_TOPIC = [
        self::TRANSITION => 'alpha-message',
    ];

    /**
     * Notify constructor.
     *
     * @param array          $config         The Priority config
     * @param OmegaSnsClient $omegaSnsClient The OmegaSnsClient
     */
    public function __construct(array $config, OmegaSnsClient $omegaSnsClient)
    {
        parent::__construct($config, $omegaSnsClient);
    }

    /**
     * Notify to Alpha|Aws on Omega transition
     *
     * @param string $transition The transition name
     * @param array  $body       The message body
     *
     * @throws \Exception
     */
    public function notifyOnTransition(string $transition, array $body)
    {
        try {
            $priority = $this->getNotificationPriority(self::TRANSITION, $transition);

            $methodMask = "notifyTo%s";
            $method     = sprintf($methodMask, ucfirst(self::PRIORITY_METHOD[$priority]));

            $this->{$method}(self::TRANSITION, $body);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Notify to Alpha
     *
     * @param string $key  The action key
     * @param array  $body The message body
     *
     * @throws \Exception
     */
    public function notifyToAlpha(string $key, array $body)
    {
        try {
            $methodMask = "notifyOn%s";
            $method     = sprintf($methodMask, ucfirst(self::ACTION_METHOD[$key]));

            AdminApi::getInstance()->{$method}($body);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Notify to Aws Sns
     *
     * @param string $key  The action key
     * @param array  $body The message body
     *
     * @throws \Exception
     */
    public function notifyToAws(string $key, array $body)
    {
        try {
            $this->omegaSnsClient->publish(self::SNS_TOPIC[$key], $body);
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
