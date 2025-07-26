# JavaScript Analyzer
[+] A simple tool to download, compare, and analyze JavaScript files from multiple projects.

---

## How to Use

1. Clone the repo:

```bash
git clone https://github.com/MahdiChangizi/javascript_analyzer.git
cd javascript_analyzer
```
2. Create Config.php with your project info and webhook URL.
```bash
touch Config.php
```
[+] Example config structure:
```php
<?php 
    return [
        'WEBHOOK_URL' => 'https://discord.com/api/webhooks/your-webhook-url',
        'PROJECT' => [
            [
                'NAME' => 'example.com',
                'SAVE_PATH' => dirname(__DIR__) . '/javascript_analyzer/storage/example.com/',
                'URL' => 'https://example.com',
                'FILE_NAMES' => [
                    'file1.js' => '/path/to/file1.js',
                    'file2.min.js' => '/path/to/file2.min.js'
                ],
            ]
        ]
    ];
?>
```
3. Make sure the storage folder is writable:
```bash
chmod -R 755 storage
```
4. Run the main script:
```bash
php Core.php
```