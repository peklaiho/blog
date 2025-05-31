---
title: "Optimizing PHP: Sorting"
date: "2020-01-12"
tags: [php, software-engineering]
---

In the [previous post](/blog/2020-01-optimizing-php-array-lookup/) we examined the potential performance issues with nested for-loops and how they can be improved using array lookups instead. Another common pitfall in terms of performance can be sorting when done incorrectly which we will examine here.

For testing, we will use a simple _SpaceShip_ class which has <var>$x</var> and <var>$y</var> coordinates, along with one method for calculating the distance between this object and the given coordinates:

```php
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
```

PHP provides the [hypot](https://www.php.net/manual/en/function.hypot) function for calculating a two-dimensional [Euclidean distance](https://en.wikipedia.org/wiki/Euclidean_distance) which we can use here. We are also using [typed properties](https://wiki.php.net/rfc/typed_properties_v2) which were added in PHP 7.4.

For testing, we are generating an array of 500k SpaceShip objects and giving them random coordinates between (-10, -10) and (10, 10). As a sidenote, if you are generating random numbers which don't need to be cryptographically secure, you should use the [mt_rand](https://www.php.net/manual/en/function.mt-rand) function which is based on the [Mersenne Twister](https://en.wikipedia.org/wiki/Mersenne_Twister) algorithm. It should be considerably faster than something like [random_int](https://www.php.net/manual/en/function.random-int.php) which generates cryptographically secure numbers.

We want to sort the array based on the distance of the ship and coordinates (0, 0) so that nearest are sorted first. Lets start by writing a simple sorting function using [usort](https://www.php.net/manual/en/function.usort.php) without thinking about optimization yet:

```php
usort($ships, function ($a, $b) {
    $ad = $a->distance(0, 0);
    $bd = $b->distance(0, 0);

    return $ad - $bd;
});
```

Then we will write some code to print the distances of the first and last 5 entries of the sorted array, so we can see if our sort is working. This is trivial so there is no need to include it here. After running the code, we get results like this:

```
$ php test1.php
Index 0: 1.0589809270992
Index 1: 0.10728402107472
Index 2: 0.14374565109018
Index 3: 1.2450498790356
Index 4: 0.37890109871559
...
Index 499995: 13.963977022865
Index 499996: 13.83056141546
Index 499997: 13.769682966212
Index 499998: 13.08788420388
Index 499999: 13.394635587768
```

Turns out our sorting is not working very well. Smaller numbers are sorted first, and larger numbers are last, but something is a bit off. The documentation for `usort` provides the reason:

> Returning non-integer values from the comparison function, such as float, will result in an internal cast to integer of the callback's return value.

Lets fix our comparison function so that it returns integer values of `0`, `-1` or `1`:

```php
usort($ships, function ($a, $b) {
    $ad = $a->distance(0, 0);
    $bd = $b->distance(0, 0);

    if ($ad == $bd) {
        return 0;
    }

    return $ad < $bd ? -1 : 1;
});
```

And lets run our test code again, but this time we will prepend it with the Linux `time` utility so we can measure the execution time:

```
$ time php test1.php
Index 0: 0.010511131291899
Index 1: 0.024306707971592
Index 2: 0.028884207430008
Index 3: 0.029837574581308
Index 4: 0.033514125418025
...
Index 499995: 14.111247184598
Index 499996: 14.118274713896
Index 499997: 14.118870471586
Index 499998: 14.12201222923
Index 499999: 14.13699710197

real    0m3.198s
user    0m3.159s
sys     0m0.037s
```

The good news is that the new comparison function seems to be working. The bad news is that this operation takes over 3 seconds to complete. We should note that since we are measuring the time for the entire script, it also includes our initialization logic (creation of objects and random numbers). For completeness, I measured everything else except sorting to take roughly 185 ms.

So how can we improve? Well, turns out that `usort` in general tends to be slow and this is a common issue I have run into. Another problem is that we have to call the `distance` method many times, in fact twice each time the comparison function is called. In this example the `distance` function is actually pretty fast, but in case it would be a computationally-heavy operation, this issue would be even more critical.

With these two things in mind, lets create an improved version of our sorting function:

```php
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
```

We are using the `&` operator before our input variable to indicate that the array is [passed by reference](https://www.php.net/manual/en/language.references.pass.php). This means we want to modify the original array given as input rather than return a new sorted array. The improved algorithm works like this:

1. Make a copy of the original array.
1. Calculate the distance for each item only once, and store them in a new array.
1. Sort the distances using [asort](https://www.php.net/manual/en/function.asort). This maintains index association of the items.
1. Update the items in the input array in order of their distance.

Lets see how much we improved:

```
$ time php test2.php
Index 0: 0.011939080326776
Index 1: 0.017192550937499
Index 2: 0.021901493915186
Index 3: 0.023347312465492
Index 4: 0.023491952527785
...
Index 499995: 14.110648942673
Index 499996: 14.115798519769
Index 499997: 14.119664541671
Index 499998: 14.12029326056
Index 499999: 14.12322177308

real    0m0.573s
user    0m0.535s
sys     0m0.037s
```

Given that our initialization logic was measured to take roughly 185ms, the improved sorting appears to take about 390ms compared to over 3s for the unoptimized version.

Hopefully this and the [previous post](/blog/2020-01-optimizing-php-array-lookup/) gave you some ideas on how to write better PHP code. And always remember to use a [profiler](/blog/2019-12-profiling-php/) to find bottlenecks in the performance of your app.
