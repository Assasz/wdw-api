<?php

namespace App\Utils\Logger;

/**
 * Class ErrorLogger
 *
 * @package App\Utils
 */
class ErrorLogger implements LoggerInterface
{
    /**
     * Logs error message in output file
     *
     * @param string $message
     */
    public function log(string $message): void
    {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $log = "[{$date}] {$message}" . PHP_EOL;

        $handle = fopen($_ENV['LOG_OUTPUT'], 'a');
        fwrite($handle, $log);
        fclose($handle);
    }
}
