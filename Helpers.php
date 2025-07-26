<?php

if (!function_exists('Logger')) {
    function Logger(string $message) {
        $here = __DIR__;
        $logFile = $here . '/loger.txt';
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] $message\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

if (!function_exists('makeDirectory')) {
    function makeDirectory($path) {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}

// if file exists
if (!function_exists('fileExists')) {
    function fileExists($filePath) {
        return file_exists($filePath);
    }
}

?>
