<?php
class SaveFiles {
    private $fileUrl;
    private $savePath;
    private $fileName;

    public function __construct($fileUrl, $savePath, $fileName) {
        $this->fileUrl = $fileUrl;
        $this->savePath = rtrim($savePath, '/') . '/';
        $this->fileName = $fileName;
    }

    public function saveFile() {
        $downloader = new DownloadFiles($this->fileUrl, $this->savePath, $this->fileName);
        return $downloader->downloadFile();
    }

    public function getFileContent() {
        $downloader = new DownloadFiles($this->fileUrl, $this->savePath, $this->fileName);
        return $downloader->getContent();
    }
}
?>
