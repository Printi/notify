<?php

namespace Printi\NotifyBundle;

use App\Services\Aws\OmegaSnsClient;

class BaseNotify
{
    /** @var array $config */
    protected $config;

    /** @var  OmegaSnsClient $omegaSnsClient */
    protected $omegaSnsClient;

    /**
     * BaseNotify constructor.
     *
     * @param array          $config
     * @param OmegaSnsClient $omegaSnsClient
     */
    public function __construct(array $config, OmegaSnsClient $omegaSnsClient)
    {
        $this->config         = $config ;
        $this->omegaSnsClient = $omegaSnsClient;
    }
}
