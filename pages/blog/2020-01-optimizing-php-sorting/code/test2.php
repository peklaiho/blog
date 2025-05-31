<?php
require('common.php');

$ships = gen_rnd_data();

function sortByDistance(array &$ships)
{
    $original = $ships;
    $distances = [];

    foreach ($ships as $ship) {
        $distances[] = $ship->distance(0, 0);
    }

    asort($distances);

    $i = 0;
    foreach ($distances as $key => $dist) {
        $ships[$i++] = $original[$key];
    }
}

sortByDistance($ships);

for ($i = 0; $i < 5; $i++) {
    $dist = $ships[$i]->distance(0, 0);
    echo "Index $i: $dist\n";
}

echo "...\n";

for ($i = count($ships) - 5; $i < count($ships); $i++) {
    $dist = $ships[$i]->distance(0, 0);
    echo "Index $i: $dist\n";
}
