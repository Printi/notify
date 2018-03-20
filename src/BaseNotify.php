<?php

namespace Printi\NotifyBundle;

use Printi\AwsBundle\Services\Sqs\Sqs;

class BaseNotify
{
    /** @var array $config */
    protected $config;

    /** @var Sqs $sqs */
    protected $sqs;


    public function __construct(array $config, Sqs $sqs)
    {
        $this->config = $config;
        $this->sqs    = $sqs;
    }
}
