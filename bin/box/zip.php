<?php

function zip($zipPath, $folder) {
    $zip = new ZipArchive();
    if (!$zip->open($zipPath, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE))
        die("Failed to create archive\n");

    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if ($file->isDir()) {
            continue;
        }
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($folder) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
        if (!$zip->status == ZIPARCHIVE::ER_OK)
            echo "Failed to write files to zip\n";
    }

    $zip->close();
}

zip($argv[1], $argv[2]);