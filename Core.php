<?php
require_once 'DownloadFiles.php';
require_once 'SaveFiles.php';
require_once __DIR__ . '/Helpers.php';


// Load environment variables
// Get file name of terminal



$fileName = 'site.min.js';
$fileUrl = 'https://toplearn.com/Site/js/' . $fileName;
$savePath = '/opt/javascript_analyzer/javascript/';


// [+] File saved successfully.\n
if ($fileUrl && $savePath) {
    $saveFiles = new SaveFiles($fileUrl, $savePath, $fileName);
    if ($saveFiles->saveFile()) {
        echo "File saved successfully.\n";
    } else {
        echo "Failed to save the file.\n";
    }
} else {
    echo "Please set FILE_URL and SAVE_PATH in the environment variables.\n";
}


?>