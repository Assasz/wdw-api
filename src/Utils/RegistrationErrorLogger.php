<?php

namespace App\Utils;

/**
 * Class RegistrationErrorLogger
 *
 * @package App\Utils
 */
class RegistrationErrorLogger
{
    /**
     * Logs registration error
     *
     * @param string $message
     */
    public function log(string $message): void
    {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $log = "[{$date}] {$message}" . PHP_EOL;

        $handle = fopen($_ENV['LOG_PATH'], 'a');
        fwrite($handle, $log);
        fclose($handle);
    }
}
