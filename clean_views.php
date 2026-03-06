<?php
$files = [
    'd:\Faizi related Data\JabulaniStore-Antigravity\jabulani-system\resources\views\frontend\products.blade.php',
    'd:\Faizi related Data\JabulaniStore-Antigravity\jabulani-system\resources\views\frontend\blog.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file))
        continue;

    $lines = file($file);
    $out = [];
    $inStyle = false;

    foreach ($lines as $line) {
        if (strpos($line, '<!-- Better Pagination -->') !== false || (strpos($line, '<style>') !== false && strpos($line, '/* Fix Laravel Default Tailwind Pagination Overrides */') !== false)) {
            $inStyle = true;
        }

        if (!$inStyle) {
            $out[] = $line;
        }

        if (strpos($line, '</style>') !== false && $inStyle) {
            $inStyle = false;
        }
    }

    file_put_contents($file, implode("", $out));
    echo "Cleaned $file\n";
}
