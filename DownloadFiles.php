<?php
require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/JavaScriptAnalysis.php';
class DownloadFiles {
    private $fileUrl;
    private $savePath;
    private $fileName;

    public function __construct($fileUrl, $savePath, $fileName) {
        $this->fileUrl = $fileUrl;
        $this->savePath = $savePath;
        $this->fileName = $fileName;
    }

    public function downloadFile() {
        $ch = curl_init($this->fileUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            // Ensure the directory exists before saving the file
            if (!file_exists(dirname($this->savePath . $this->fileName))) {
                makeDirectory(dirname($this->savePath . $this->fileName));
            }

            // Check if the file already exists and is identical
            if (fileExists($this->savePath . $this->fileName)) {
                $analysis = new JavaScriptAnalysis($this->savePath , $this->fileName, $data);
                $hasChanges = $analysis->analyzeChanges();
            }

            file_put_contents($this->savePath . $this->fileName, $data);
            return true;
        }
        return false;
    }

    public function getContent() {
        return file_get_contents($this->savePath . $this->fileName);
    }
}
 
?>