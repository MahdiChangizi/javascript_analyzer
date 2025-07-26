<?php
require_once 'DownloadFiles.php';
require_once 'SaveFiles.php';
require_once __DIR__ . '/Helpers.php';

$config = require 'Config.php';
$projects = $config['PROJECT'];

foreach ($projects as $project) {
    $projectName = $project['NAME'];
    $baseUrl = $project['URL'];
    $saveBasePath = $project['SAVE_PATH'];
    $fileNames = $project['FILE_NAMES'];

    foreach ($fileNames as $fileName => $filePath) {
        $fileUrl = $baseUrl . $filePath;
        $savePath = $saveBasePath . $fileName . '/';

        if ($fileUrl && $savePath) {
            $saveFiles = new SaveFiles($fileUrl, $savePath, $fileName);
            if ($saveFiles->saveFile()) {
                echo "[$projectName] $fileName saved successfully.\n";
            } else {
                echo "[$projectName] Failed to save $fileName.\n";
            }
        } else {
            echo "Please set FILE_URL and SAVE_PATH in the environment variables.\n";
        }
    }
}
?>
