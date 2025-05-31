<?php
require('common.php');

$ships = gen_rnd_data();

usort($ships, function ($a, $b) {
    $ad = $a->distance(0, 0);
    $bd = $b->distance(0, 0);

    if ($ad == $bd) {
        return 0;
    }

    return $ad < $bd ? -1 : 1;
});

for ($i = 0; $i < 5; $i++) {
    $dist = $ships[$i]->distance(0, 0);
    echo "Index $i: $dist\n";
}

echo "...\n";

for ($i = count($ships) - 5; $i < count($ships); $i++) {
    $dist = $ships[$i]->distance(0, 0);
    echo "Index $i: $dist\n";
}
