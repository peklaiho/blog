<?php

class SpaceShip
{
    public float $x;
    public float $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function distance(float $x, float $y): float
    {
        return hypot($x - $this->x, $y - $this->y);
    }
}

function gen_rnd_data()
{
    $ships = [];

    for ($i = 0; $i < 500000; $i++) {
        $x = -10 + 20 * (mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax());
        $y = -10 + 20 * (mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax());

        $ships[] = new SpaceShip($x, $y);
    }

    return $ships;
}
