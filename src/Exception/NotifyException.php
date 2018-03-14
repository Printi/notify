<?php

namespace Printi\NotifyBundle\Exception;

/**
 * Class NotifyException
 */
class NotifyException extends AbstractException
{
    const TYPE_NOTIFY_INVALID_ARGUMENTS  = "NOTIFY_INVALID_ARGUMENTS";
    const TYPE_NOTIFY_PRIORITY_NOT_FOUND = "NOTIFY_PRIORITY_NOT_FOUND";

    /**
     * @inheritDoc
     */
    protected function populateCodeMap()
    {
        $this->codeMap = [
            self::TYPE_NOTIFY_INVALID_ARGUMENTS  => 400,
            self::TYPE_NOTIFY_PRIORITY_NOT_FOUND => 400,
        ];
    }
}
