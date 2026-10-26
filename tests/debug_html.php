<?php
$html = file_get_contents('http://127.0.0.1:8000/');
preg_match_all('/<link[^>]+>/i', $html, $links);
preg_match_all('/<img[^>]+>/i', $html, $imgs);
preg_match_all('/<source[^>]+>/i', $html, $sources);

echo "--- STYLESHEET LINKS ---\n";
foreach ($links[0] as $l) {
    if (strpos($l, 'stylesheet') !== false) {
        echo $l . "\n";
    }
}

echo "\n--- IMAGE TAGS ---\n";
foreach (array_slice($imgs[0], 0, 5) as $img) {
    echo $img . "\n";
}

echo "\n--- SOURCE TAGS ---\n";
foreach ($sources[0] as $src) {
    echo $src . "\n";
}
