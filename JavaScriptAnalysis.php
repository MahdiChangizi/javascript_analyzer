<?php

class JavaScriptAnalysis {
    private $filePath;
    private $fileName;
    private $originalContent;
    private $updatedContent;
    private $data;
    private $config;

    public function __construct($filePath, $fileName, $data) {
        $this->filePath = rtrim($filePath, '/') . '/';
        $this->fileName = $fileName;
        $this->originalContent = file_get_contents($this->filePath . $this->fileName);
        $this->config = require __DIR__ . '/Config.php';
        $this->data = $data;
    }

    public function analyzeChanges(): bool {
        $this->updatedContent = $this->data;

        if ($this->originalContent === $this->updatedContent) {
            Logger("File {$this->filePath}{$this->fileName} has not changed.");
            return false;
        }

        $this->makeNewFile();
        $this->makeOldAndCreateNewFile();

        $diffReport = $this->generateDiff();
        $this->logDiffToFile($diffReport);
        $this->sendNotification($diffReport);

        Logger("File updated: {$this->fileName}");
        return true;
    }

    public function makeNewFile(): void {
        $file_name = $this->filePath . 'new_' . $this->fileName;
        file_put_contents($file_name, $this->updatedContent);
        Logger("New file created at {$file_name}");
    }

    private function generateDiff(): string {
        $old = $this->originalContent;
        $new = $this->updatedContent;

        if ($old === $new) return "No changes.";

        $minLength = min(strlen($old), strlen($new));
        $start = 0;

        while ($start < $minLength && $old[$start] === $new[$start]) {
            $start++;
        }

        $endOld = strlen($old) - 1;
        $endNew = strlen($new) - 1;
        while ($endOld > $start && $endNew > $start && $old[$endOld] === $new[$endNew]) {
            $endOld--;
            $endNew--;
        }

        $oldDiff = substr($old, $start, $endOld - $start + 1);
        $newDiff = substr($new, $start, $endNew - $start + 1);

        $before = substr($old, max(0, $start - 30), 30);
        $after = substr($old, $endOld + 1, 30);

        $trimMarker = '<<<trimmed>>>';
        $limit = 20;

        if (strlen($before) > $limit) $before = substr($before, 0, $limit) . $trimMarker;
        if (strlen($after) > $limit)  $after  = substr($after, 0, $limit) . $trimMarker;
        if (strlen($oldDiff) > $limit) $oldDiff = substr($oldDiff, 0, $limit) . $trimMarker;
        if (strlen($newDiff) > $limit) $newDiff = substr($newDiff, 0, $limit) . $trimMarker;

        $formattedDiff = "**[+] File `{$this->fileName}` has been updated:**\n";
        $formattedDiff .= "```diff\n";
        $formattedDiff .= "- ...{$before}[{$oldDiff}]{$after}...\n";
        $formattedDiff .= "+ ...{$before}[{$newDiff}]{$after}...\n";
        $formattedDiff .= "```";

        return $formattedDiff;
    }

    private function logDiffToFile(string $diff): void {
        $logFile = $this->filePath . 'diff_log.txt';
        $timestamp = date("Y-m-d H:i:s");
        $log = "===== DIFF LOG ({$timestamp}) for {$this->fileName} =====\n" . $diff . "\n\n";
        file_put_contents($logFile, $log, FILE_APPEND);
        Logger("Diff log saved: {$logFile}");
    }

    public function sendNotification(string $diffSummary): void {
        $webhookUrl = $this->config['WEBHOOK_URL'];

        $data = [
            'content' => substr($diffSummary, 0, 1900)
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            ]
        ];

        $context  = stream_context_create($options);
        file_get_contents($webhookUrl, false, $context);
    }

    public function makeOldAndCreateNewFile(): void {
        $old_file_name = $this->filePath . 'oldFile_' . $this->fileName;
        file_put_contents($old_file_name, $this->originalContent);
        Logger("Old file created at {$old_file_name}");

        $new_file_name = $this->filePath . 'new_' . $this->fileName;
        if (file_exists($new_file_name)) {
            unlink($new_file_name);
            Logger("Removed new file at {$new_file_name}");
        }

        file_put_contents($this->filePath . $this->fileName, $this->updatedContent);
    }
}
?>
