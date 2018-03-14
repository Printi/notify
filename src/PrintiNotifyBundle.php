<?php

namespace Printi\NotifyBundle;

use Printi\NotifyBundle\DependencyInjection\NotifyExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PrintiNotifyBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new NotifyExtension();
    }
}
