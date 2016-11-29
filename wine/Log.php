<?php
namespace wine;

/**
 * Write Log for debugging and error
 */
class Log{
    /**
     * write log info to given file
     * @param type $file
     * @param type $log_str
     */
    public static function logInfo($file, $log_str){
        file_put_contents($file.'.log', $log_str.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
}

