---
title: "Writing a Lisp Interpreter"
date: "2020-10-24"
tags: [php, lisp, software-engineering]
---

There comes a time in the life of a serious software engineer when he wants to implement a custom programming language. Not with the goal of creating a language that gains widespread adoption (there are enough programming languages already), but as an exercise to gain deeper insight into how programming languages work.

My time came when I stumbled upon the [Make a Lisp](https://github.com/kanaka/mal) project which contains detailed instructions on how to write your own [Lisp](https://en.wikipedia.org/wiki/Lisp_%28programming_language%29) language. I ended up using that project as a guide, but not following their conventions strictly, instead coming up with a totally custom Lisp-like language. I wrote the interpreter in PHP and named it [MadLisp](https://bitbucket.org/maddy83/madlisp/).

Lisp is an ancient family of programming languages which dates back to 1958. My previous experience with Lisp has been mostly with [Emacs Lisp](https://www.gnu.org/software/emacs/manual/html_node/elisp/index.html) which is used to configure the [Emacs](https://www.gnu.org/software/emacs/) text editor which I use for software development. I have also played around a little with [Clojure](https://clojure.org/) but it never really took off for me. It is a great language, but I am not a big fan of the Java ecosystem which it runs on.

I like Lisp because the syntax is simple and minimalistic. It encourages (or even enforces) the [functional programming](https://en.wikipedia.org/wiki/Functional_programming) paradigm. A Lisp program is basically just a collection of lists and functions which manipulate those lists. Usually simple and generic functions are created first, and then other functions composed of those simpler functions, gradually increasing in complexity. Data is immutable by default. Recursion is preferred instead of explicit loops.

Another feature of Lisp languages is that they are fun to use with a [REPL](https://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop) which stands for a Read-Eval-Print-Loop. The REPL is an interactive command prompt where statements can be typed and evaluated one by one. It is a great tool for [exploratory programming](https://en.wikipedia.org/wiki/Exploratory_programming). The Read, Eval and Print functions are the core of a Lisp interpreter:

1. The Read function takes input as a string, [tokenizes](https://en.wikipedia.org/wiki/Lexical_analysis) it, and returns an [abstract syntax tree](https://en.wikipedia.org/wiki/Abstract_syntax_tree) (AST) as a result.
2. The Eval function takes an AST and evaluates it. All function calls are executed recursively.
3. The Print function prints the result of the Eval function. Hierarchical data is printed recursively.

A Lisp program is composed of [S-expressions](https://en.wikipedia.org/wiki/S-expression). Whenever the Eval function encounters a list, the first item of that list is treated as a function and the remaining items as arguments to that function. The function is then called and the result is returned.

In the following examples the `> ` denotes the REPL prompt. Calculating the sum of two numbers work like this:

```
> (+ 2 3)
5
```

This is in contrast to many common languages where the syntax would be `2 + 3`. Note that `+` is just an ordinary function, not an operator.

We can skip evaluation using the `'` (single quote) symbol as a prefix. So by quoting a list, we can return it directly without evaluation.

```
> '(1 2 3)
(1 2 3)
```

Trying to evaluate a list where the first item does not correspond to a function will result in an error:

```
> (1 2 3)
error: eval: first item of list is not function
```

New symbols can be defined in the current environment using the special form `def`. Functions can be defined using the special form `fn`. For example, a function to multiply a number by itself could be defined like this:

```
(def a2 (fn (a) (* a a)))
```

And used like this:

```
> (a2 5)
25
```

Or like this, utilizing the [MapReduce](https://en.wikipedia.org/wiki/MapReduce) algorithm:

```
> (reduce + (map a2 '(1 2 3 4 5)))
55
```

For more examples, please check out the [MadLisp repository](https://bitbucket.org/maddy83/madlisp/).

This has been a very fun project for me. Now I have a working language which is perfectly suitable for scripting and small tasks like that. If you wish to embark on this journey of writing your own language, remember that it should be about having fun and about learning how programming languages work. Don't worry about adoption, in fact assume no-one else will use it. Don't worry about performance too much. Of course it needs to run somewhat fast in order to be usable, but you are probably not going to write any performance-critical applications in it. For that we already have C, [Go](https://golang.org/) and others.
