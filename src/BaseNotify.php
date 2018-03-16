<?php

namespace Printi\NotifyBundle;

use Printi\AwsBundle\Services\Sns\Sns;

class BaseNotify
{
    /** @var array $config */
    protected $config;

    /** @var Sns $sns */
    protected $sns;


    public function __construct(array $config, Sns $sns)
    {
        $this->config = $config;
        $this->sns    = $sns;
    }

    public function publishAwsSns(string $topic, array $message = [])
    {

    }
}
