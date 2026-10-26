<?php
$dir = __DIR__ . '/../public/assets/images/client';
$files = glob("$dir/image-*.jpg");
natsort($files);

$catalog = [];
foreach ($files as $f) {
    $name = basename($f);
    $sz = getimagesize($f);
    $catalog[] = [
        'file' => $name,
        'width' => $sz[0],
        'height' => $sz[1],
        'ratio' => round($sz[0] / $sz[1], 2),
        'size_kb' => round(filesize($f) / 1024)
    ];
}

file_put_contents(__DIR__ . '/image_catalog.json', json_encode($catalog, JSON_PRETTY_PRINT));
echo "Indexed " . count($catalog) . " images\n";
