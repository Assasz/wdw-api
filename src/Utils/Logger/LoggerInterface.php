<?php

namespace App\Utils\Logger;

/**
 * Interface LoggerInterface
 *
 * @package App\Utils\Logger
 */
interface LoggerInterface
{
    /**
     * Logs message in output file
     *
     * @param string $message
     */
    public function log(string $message): void;
}
