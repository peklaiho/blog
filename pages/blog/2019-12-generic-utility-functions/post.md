---
title: "Generic Utility Functions for PHP"
date: "2019-12-16"
tags: [php, software-engineering]
---

On this post I would like to introduce some generic utility functions for PHP. I often keep these in the global namespace because they are so useful. You may wish to wrap them in a namespace, or even a class, depending on your use case. They follow similar naming convention as the built-in functions of PHP because I view them almost like extensions to the standard library.

I would caution though that many of these functions are written in functional programming style, utilizing [array_map](https://www.php.net/manual/en/function.array-map.php) and similar functions. In general this functional style tends to be slower in PHP than performing the same tasks using foreach loops. So if performance is critical to your application, you should prefer looping over "mapping". Of course you should [profile](/blog/2019-12-profiling-php/) the performance of your app properly to find any issues.

I would not recommend copy-pasting my code (or any code for that matter) directly, without taking the time to understand it, and making changes for your particular use-case if needed. I will attempt to accompany each function with a few words of explanation.

## String functions

A recurring need seems to be to test if a string starts or ends with another string. The standard library provides [strcmp](https://www.php.net/manual/en/function.strcmp.php) for testing equality and [strpos](https://www.php.net/manual/en/function.strpos.php) for finding a substring, but nothing that does what we need out-of-the-box.

If you are dealing with strings that have multi-byte characters, you should be using the [mbstring](https://www.php.net/manual/en/book.mbstring.php) extension, and replacing these with [mb_strlen](https://www.php.net/manual/en/function.mb-strlen.php), [mb_substr](https://www.php.net/manual/en/function.mb-substr.php) and so on.

```php
function str_starts_with(string $str, string $start, bool $ignoreCase = false): bool
{
    if ($ignoreCase) {
        $str = strtolower($str);
        $start = strtolower($start);
    }

    return substr($str, 0, strlen($start)) === $start;
}

function str_ends_with(string $str, string $end, bool $ignoreCase = false): bool
{
    if ($ignoreCase) {
        $str = strtolower($str);
        $end = strtolower($end);
    }

    return substr($str, strlen($str) - strlen($end)) === $end;
}
```

**Update:** The `str_starts_with` and `str_ends_with` functions were added in PHP 8. Here is a link to the [RFC document](https://wiki.php.net/rfc/add_str_starts_with_and_ends_with_functions). The new function names and arguments match with my functions, except the `$ignoreCase` argument is not supported. If you need to write code that is compatible with both PHP 7 and 8, you can use [function_exists](https://www.php.net/manual/en/function.function-exists.php) to check if a function has been defined, and define it only in case it is missing.

## Array functions

Arrays have very good performance in PHP thanks to their underlying C implementation and are used pretty much everywhere. It's therefore no surprise that there are some recurring cases for extending the array functions of the standard library.

With the introduction of the [arrow syntax](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.arrow-functions) for defining simple functions in PHP 7.4, some of these are not quite so useful anymore. But if you are not using 7.4 yet, the old syntax for defining closures can get tedious.

The function `array_fetch` returns values from a two-dimensional array by the given key:

```php
function array_fetch(array $arr, string $key): array
{
    return array_map(function ($a) use ($key) {
        return $a[$key];
    }, $arr);
}
```

And if we want to return multiple values, we can use `array_fetch_many`. Here we use [array_flip](https://www.php.net/manual/en/function.array-flip.php) to exchange the keys and values of <var>$keys</var> and then [array_intersect_key](https://www.php.net/manual/en/function.array-intersect-key.php) to return only keys from <var>$a</var> which are present in the flipped array.

```php
function array_fetch_many(array $arr, array $keys): array
{
    return array_map(function ($a) use ($keys) {
        return array_intersect_key($a, array_flip($keys));
    }, $arr);
}
```

Sometimes it's useful to know how many of given keys are contained in an array. Here we just use [array_key_exists](https://www.php.net/manual/en/function.array-key-exists.php), take the integer value of that using [intval](https://www.php.net/manual/en/function.intval.php) which is 0 or 1, and then just sum them up using [array_sum](https://www.php.net/manual/en/function.array-sum.php).

```php
function array_contains_keys(array $arr, array $keys): int
{
    return array_sum(array_map(function ($a) use ($arr) {
        return intval(array_key_exists($a, $arr));
    }, $keys));
}
```

Here is a handy one-liner to convert a two-dimensional array into a one-dimensional array.

I must caution that the [illuminate/support](https://github.com/illuminate/support) library used to define `array_flatten` in the global namespace, so you may get a collision if using that. Personally, I just hate it when libraries define functions in the global namespace. However, looking at their code now, the functions appear neatly wrapped in a class, so perhaps they have fixed their nasty habits.

```php
function array_flatten(array $arr): array
{
    return array_reduce($arr, 'array_merge', []);
}
```

But what if you want to create a two-dimensional array from one-dimensional array, and group the results by the given key? We have the `array_group` for that. Yes, we have the looping version here which is not quite as elegant, but at least it's fast.

```php
function array_group(array $arr, string $keyName): array
{
    $result = [];

    foreach ($arr as $a) {
        $key = $a[$keyName];
        $result[$key][] = $a;
    }

    return $result;
}
```

Sometimes it's useful to read a value from an array and unset it at the same time. We have `array_peel` for that. Please note that we have to use the &-sign in front of <var>$arr</var> to pass it by reference, because we want to modify the original array.

```php
function array_peel(array &$arr, string $key)
{
    $val = $arr[$key];
    unset($arr[$key]);
    return $val;
}
```

Finally, we have `array_strip` which removes the given keys from a two-dimensional array and returns the rest. First we flip the keys and values of <var>$keys</var> and then use [array_diff_key](https://www.php.net/manual/en/function.array-diff-key.php) to return only those keys of <var>$a</var> which are _not_ present in the flipped array.

```php
function array_strip(array $arr, array $keys): array
{
    return array_map(function ($a) use ($keys) {
        return array_diff_key($a, array_flip($keys));
    }, $arr);
}
```

Of course these functions are simple and do not provide much value as themselves because they are fast to write from scratch. Perhaps the real purpose of this post is to encourage the _paradigm_ of creating small functions for things which are constantly repeating in your code. This will result in a cleaner codebase in the long run which is easier to maintain.
