---
title: "Customizing Indentation in Emacs CC Mode"
date: "2020-10-25"
tags: [emacs, php, software-engineering]
---

I have been using [Emacs](https://www.gnu.org/software/emacs/) as my main text editor for writing PHP code. For that purpose, the [php-mode](https://github.com/emacs-php/php-mode) is a nice Major Mode which provides syntax highlighting and other convenient features. It can be installed from the [MELPA](https://melpa.org/) package repository.

It provides several different styles for indentation and formatting out of the box. You can enable one that you want to use in your `php-mode-hook` function. I prefer to use the [PSR-2](https://www.php-fig.org/psr/psr-2/) coding style in my projects.

There is a particular case with the default indentation rules that I am not fond of. Whenever I define an array or an argument list, where the first item is not on a new line, the indentation of the remaining items is lined up with the first item. For example:

```php
$arr = [ 1,
         2,
         3
];

$result = myFunc(4, 5,
                 "longer string argument"
);
```

Instead, I would prefer that the subsequent lines are indented just one standard indentation level, or 4 spaces in this case.

The major mode does have a setting called `php-mode-lineup-cascaded-calls` which is related, but does not help here. That setting controls whether method calls are lined up with each other, but it does not affect function arguments or array items. Also it is disabled by default.

So we have to dig a little deeper. PHP mode is derived from CC mode which is a built-in mode in Emacs for all C-like languages. It has a complex [indentation engine](https://www.gnu.org/software/emacs/manual/html_node/ccmode/Indentation-Engine-Basics.html) which is highly [customizable](https://www.gnu.org/software/emacs/manual/html_node/ccmode/Customizing-Indentation.html). The following applies to all major modes which are derived from CC mode, not just PHP.

We can start by examining the variable `c-offsets-alist` using <kbd>C-h v</kbd>. There is a very long list of different syntax elements for which we can define separate indentation rules. We can also see the current values for the various syntax elements.

First we have to determine which syntax element the line is being recognized as that we want to change. Bring the cursor on that line and press <kbd>C-c C-s</kbd> to run syntactic analysis for that line. In the example code above the element we need to modify is `arglist-cont-nonempty`.

Looking at the variable `c-offsets-alist`, we can see the current value for that syntax element:

```lisp
(arglist-cont-nonempty first php-lineup-cascaded-calls php-c-lineup-arglist)
```

The `first` symbol means that the first function is used for indentation which does not return nil. The `php-lineup-cascaded-calls` function is controlled by the variable we discussed earlier and is not used (meaning it should return nil, and be ignored). The function that is being used to determine indentation in this case is `php-c-lineup-arglist` which internally calls `c-lineup-arglist` provided by CC mode.

The nice thing about CC mode is that we can change and test the indentation settings interactively. Having the cursor on that same line, we can use <kbd>C-c C-o</kbd> to change the indentation settings. First it will prompt for the syntactic symbol to change, which will default to the one being used for that line. Then it will ask what indentation rule to apply. This is a run-time change that only applies for the current buffer.

We can use the special symbol `+` which means to add one level of indentation. In fact, at first glance, this gives the indentation that we want:

```php
$arr = [ 1,
    2,
    3
];

$result = myFunc(1, 2,
    "longer string argument"
);
```

The problem with this is that if we have two levels of function calls, we also get two levels of indentation, which is not what we wanted:

```php
$result = myFunc(anotherFunc(1, 2,
        "longer string argument"
));
```

The syntactic analysis indeed shows that we have two levels of `arglist-cont-nonempty` elements.

We know that we get the indentation that we want when the first argument is on a new line. We can check that again with <kbd>C-c C-s</kbd> and it returns `arglist-intro`. That is being currently handled by the function `php-lineup-arglist-intro`. For now, I am going to fix the issue by simply using this same function for the `arglist-cont-nonempty` case.

In order to make the change permanent and default for new buffers, we need to set it in our `php-mode-hook` function. Modifying the indentation rules is done by the `c-set-offset` function:

```lisp
(c-set-offset 'arglist-cont-nonempty 'php-lineup-arglist-intro)
```

This seems to finally give what I want:

```php
$arr = [[ 1,
    2,
    3
]];

$result = myFunc(anotherFunc(1, 2,
    "longer string argument"
));
```

I will use this for now, but I am not sure if it will work correctly for all cases. That remains to be seen, but so far it seems good.

The purpose of this post was not so much to fix a specific issue in php-mode, but to learn about customizing indentation interactively using <kbd>C-c C-s</kbd> and <kbd>C-c C-o</kbd> in any CC-derived major modes. CC mode also comes with many built-in [lineup functions](https://www.gnu.org/software/emacs/manual/html_node/ccmode/Line_002dUp-Functions.html) which should handle many common use-cases.

As a final tip, the `indent-region` command is useful while testing different settings, possibly combined with <kbd>C-x h</kbd> for selecting the whole buffer first.
