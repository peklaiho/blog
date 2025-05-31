---
title: "Optimizing PHP: Array Lookups Instead of Nested Loops"
date: "2020-01-11"
tags: [php, software-engineering]
---

One of the most common issues that I see almost daily when reviewing code is nested loops where they aren't required. They are particularly tricky because they are not slow with small data sets (which developers often use for testing), but become slow in production environment where data sets are larger. Of course, testing with too small data sets is a separate problem which should be fixed as well.

A good example of this is when we have two arrays with related data and they need to be checked against each other somehow. Thus we are required to loop over each array and compare the items. As a simple example, I am going to use an array of invoices and an array of payments. The payments have a `invoiceId` property which points to the `id` property of the related invoice.

As a data set for testing, I am going to generate 25k of both invoices and payments, and assign the `invoiceId` of each payment to a random invoice. The goal is to check how many of these invoices are paid. This is simple to do with two nested loops:

```php
$paid = 0;

foreach ($invoices as $invoice) {
    foreach ($payments as $payment) {
        if ($invoice->id == $payment->invoiceId) {
            $paid++;
            break;
        }
    }
}

echo "Paid invoices: $paid\n";
```

In this simplified example we just consider an invoice paid if it has at least one payment. This means we can use the `break` directive to stop the inner loop after first payment is found. This is a good optimization also, because it means we are not iterating the inner loop until the end every time.

Next we need to measure the execution time of our code. Of course, we could measure the time in the PHP code itself, using [microtime](https://www.php.net/manual/en/function.microtime.php) to record the start and end times. But in Linux we have a handy `time` command which we can use to measure execution times. We just prepend `time` before the command we want to execute:

```
$ time php test1.php
Paid invoices: 15738

real    0m6.537s
user    0m6.525s
sys     0m0.007s
```

So it takes about six and a half seconds to execute on my machine. This is of course unacceptably slow way to figure out how many of 25k invoices are paid (still a fairly small data set). We can do better. Here is the improved version:

```php
$invoicePayments = [];

foreach ($payments as $payment) {
    $invoicePayments[$payment->invoiceId][] = $payment;
}

$paid = 0;

foreach ($invoices as $invoice) {
    $pmts = $invoicePayments[$invoice->id] ?? [];

    if (count($pmts) > 0) {
        $paid++;
    }
}

echo "Paid invoices: $paid\n";
```

First we make a new two-dimensional array `$invoicePayments` which contains payments indexed by `invoiceId`. In the second loop we can just get the payments for each invoice using _array lookup_. Note that we use the `??` operator to provide an empty array in case this invoice has no payments. Lets measure the execution time again.

```
$ time php test2.php
Paid invoices: 15779

real    0m0.038s
user    0m0.032s
sys     0m0.006s
```

Now we are using only 38 milliseconds for the same operation. Why is this so much faster? Because we have two sequential loops instead of nested loops. So we are performing a **O(2n)** operation, instead of **O(n<sup>2</sup>)**. And array lookup is terribly fast in PHP. In fact a good rule of thumb is to use array lookups whenever possible.

Since indexing an array by some given key is so useful, you can consider making a generic utility function for this purpose. I introduced the `array_group` function in a [previous post](/blog/2019-12-generic-utility-functions/) as an example.

Hopefully this post gives some warning about the dangers of nested loops as well as ideas on how to improve them. And don't let your peers off the hook so easily if you see nested loops during code reviews. :) Also check out my next post where I write about [sorting](/blog/2020-01-optimizing-php-sorting/).
