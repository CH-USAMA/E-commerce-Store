<?php

$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

if (file_exists($link)) {
    echo "Storage link already exists.\n";
    exit;
}

if (symlink($target, $link)) {
    echo "Storage link created successfully.\n";
} else {
    echo "Failed to create storage link. Please check permissions or manually link storage/app/public to public/storage.\n";
}
