---
title: "Thoughts on Programming Languages"
date: "2024-09-07"
tags: [software-engineering]
---

This is a summary post of different programming languages as well as my personal experiences and opinions about them. I have written code in many languages over the years. Some languages I have tried only briefly while others I have used daily for many years. Additionally there are many interesting languages that I still look forward to learning. I have written before about [what makes a good programming language](/blog/2021-08-what-is-best-programming-language/) which is closely related to this post. This post is very long, so if you just want to know which programming language you should be learning, skip to the very end where I give some recommendations for different career paths.

New languages are popping up everywhere right now, more so than at any other time during my career as a software engineer. It is a very interesting time to be in the industry. There is always some hot new thing to try out. On the other hand, this can be very distracting, so often it is better to focus on learning the mature languages that have been around for decades. They are not going to disappear anytime soon. Also it is important to realize that it is not possible to learn every language because there is just not enough time. It is much better to become specialized in few languages rather than scratch the surface of every language. Generally software engineers are paid the big bucks for being really good at *one specific thing*, not for being a *jack-of-all-trades*. If you are at the beginning of your career, you should focus on learning just *one* programming language really well.

The primary purpose of a programming language is to translate ideas from the human mind into a format that the computer can understand. Different languages have different properties and different ways to accomplish this goal. But at the end of the day, all of them basically do the same thing. A good analogy to programming languages is automobile brands. Is BMW better than Audi? Is Audi better than Mercedes? All of the cars can get you from point A to point B, which is the primary purpose of an automobile. So, for most of the time, it doesn't matter *too much* which one you choose. Then there are other times when it *does matter*. If you are choosing a race car instead of going grocery shopping, then the car better be fast.

All programmers have their own preferences about which language is "good" and which language is "bad". These preferences are often subjective and affected by many biases in our thinking. How people end up using a specific language, or driving a specific car, is often guided by small random events rather than a rational selection process. A man drives a BMW because that is the brand his father drove so that is the one he grew up with. A programmer uses Python because that is what he was taught at his university. And both are fine choices. But eventually it is helpful to think more deeply about different choices available to us and how they differ from each other.

The most general distinction is whether a language is a high-level or a low-level language. A low-level language gives the programmer more fine-grained control over the CPU but results in more verbose code. A high-level language gives the programmer less direct control over what the CPU is doing but also requires writing less code to accomplish a task. This is usually a trade-off between *development speed* and *runtime speed*. Writing a program in a low-level language takes longer but runs faster while the opposite is true for a high-level language. A good programmer should have both types of languages in his toolbag because they excel in different situations.

The low-level languages are usually *compiled languages*, which means they have a *compiler* that translates the programming language into machine code. The CPU can read and understand machine code directly, so it can take the executable that the compiler produced and just begin executing it as-is. Using a compiled language means that there is a separate compilation step before running a program. Examples of compiled languages would be **C** and **Go**.

Higher level languages are often *interpreted languages*. Instead of using a compiler, they have an *interpreter*. The interpreter reads the code and translates it into machine code on-the-fly as the program is running. This is the reason why an interpreted language is often orders of magnitude slower than a compiled one. Eventually it has to do the heavy lifting that the compiled language did beforehand. But using an interpreted language is very convenient because one can just write a program and run it immediately as is. Examples of interpreted languages would be **Python** and **PHP**.

Finally there is a third class of languages which could be described as *intermediate languages* between compiled and interpreted ones. They use a compiler also but the compiler does not output machine code directly. Instead the compiler outputs intermediate code that is often called *bytecode*. The CPU cannot understand this bytecode directly so these languages always require a separate *runtime*, or *virtual machine*, to execute the compiled code. These intermediate languages run much faster than interpreted languages but slower than those that compile directly to machine code. Examples of intermediate languages would be **Java** and **C#**.

Another difference between languages is their type system. Lower level languages are usually *statically typed* which means that a variable has a strict type that cannot be changed after it has been declared. And declarations usually require types to be defined explicitly, though sometimes the compiler can infer them. Higher level languages are often *dynamically typed* which means that the types are handled behind the scenes. In such languages you can assign an integer into a variable and then later assign a string to the same variable. This would not be possible in a compiled language because the compiler needs to allocate the right amount of memory for each type beforehand. Both type systems have their benefits and drawbacks. Writing code without explicitly declaring types is naturally faster and more convenient. On the other hand it is more error-prone and type errors are caught only at runtime when the code is already running.

Now a summary of some popular programming languages and my thoughts about each of them.

## The Holy Trinity

In my opinion there are three programming languages that deserve a special place at the top of the list: **Assembly**, **C** and **C++**. I would argue that they are the most important and successful programming languages. Most of the software powering the devices of our world is written in C or C++. This holds true whether those devices are personal computers, mobile phones, servers, supercomputers or embedded devices.

### Assembly

I think a proper summary of programming languages should start with [Assembly](https://en.wikipedia.org/wiki/Assembly_language). It is the most low-level of programming languages. In modern times it is generally only used by engineers who work on compilers, operating systems, device drivers or embedded devices. It is the only language that allows direct control of the CPU and that makes it different from all the others. A program called *assembler* translates the assembly language into machine code. But unlike compilers, the assembler does not need to do much heavy lifting because the assembly language is similar to machine code to begin with. [NASM](https://nasm.us/) is an assembler that I have used before and recommend.

Looking at assembly language for the first time can feel intimidating for someone with a background in high-level languages. But once you get into it and get your hands dirty, it quickly starts to make sense. Yes, there are a lot of instructions in the [x86 instruction set](https://www.felixcloutier.com/x86/), but you only need a dozen or so to start writing basic applications. Most of the instructions are optimizations, important for professional compiler developers, but not for someone just getting started. I would highly recommend that every senior-level software engineer learns the basics of assembly. It is invaluable for understanding how the computer works. I have written a [tutorial for getting started with Assembly](/blog/2021-08-getting-started-with-x64-assembly/) that may be a good place to start.

There are also games which allow you to learn a language similar to assembly in a gamified environment. Sometimes playing a game is a more fun way to learn because then you have ready-made puzzles to solve. [Zachtronics](https://www.zachtronics.com/) has created many games for this purpose such as [TIS-100](https://store.steampowered.com/app/370360/TIS100/), [Shenzhen I/O](https://store.steampowered.com/app/504210/SHENZHEN_IO/) and [Exapunks](https://store.steampowered.com/app/716490/EXAPUNKS/). I highly recommend all of them.

### C

The C language appeared in 1972 according to [Wikipedia](https://en.wikipedia.org/wiki/C_%28programming_language%29) and it is still widely used today. It has certainly stood the test of time. Pretty much everything in the Unix and GNU/Linux ecosystem is written in C. This includes the [Linux kernel](https://www.kernel.org/), [GCC compiler](https://gcc.gnu.org/), [C library](https://www.gnu.org/software/libc/), [GNU coreutils](https://www.gnu.org/software/coreutils/) and so forth. C is a low-level language that does not provide many guardrails like newer languages do. For example, the programmer is responsible for freeing allocated memory because there is no garbage collection. On the other hand, C code is fast, because there is not much overhead. I think a good analogy is a very sharp knife. A sharp knife is an excellent tool *in the right hands*, but will cause self-inflicted wounds in the wrong hands.

The C language was also my first contact with programming. When I was still a kid, I was heavily into playing [MUD](https://en.wikipedia.org/wiki/Multi-user_dungeon) games. MUDs are text-based multiplayer games that can be played with a standard Telnet client. This was convenient back in the day: there was no need to install any client software and every computer had a Telnet client. My first steps into programming were edits to the source code of [CircleMUD](https://www.circlemud.org/). The modern version of Circle is [tbaMUD](https://github.com/tbamud/tbamud). I did not understand much about programming, but I could make small edits to the source code and compile it. I barely understood what I was doing and writing something from scratch would have been impossible. Now programming has become my career and passion. Big journeys truly start with small steps.

### C++

C++ was created by [Bjarne Stroustrup](https://www.stroustrup.com/) and first appeared in 1985. The language is actively developed and a new version of the C++ standard is release every 3 years. They are named C++17, C++20, C++23 and so on. Because the language has been in active development for so long, and because maintaining backwards compability is very important, the language has accumulated lots of features and even different ways of doing the same thing. This is generally the most common criticism of C++. As a counter-argument, C++ has a policy that "you only pay for what you use". In other words, there may be lots of features, but there is zero overhead performance-wise from those features that you do not use.

I like C++ and would like to use it more in the future. So far I have only used it for small hobby projects like a [text editor](https://github.com/peklaiho/med). I would encourage newer programmers to give C++ a chance and not throw it away as obsolete. However, there are a handful of things to understand before you can be efficient with it, so it is not the easiest language to learn as a beginner. If you have a C project, you can take that as a base and start to use C++ features incrementally because C++ is backwards compatible with C. But the recommended way to write modern C++ looks very different from C (for example the use of pointers is discouraged). A good place to start learning the recommended practices of modern C++ is the [C++ Core Guidelines](https://isocpp.github.io/CppCoreGuidelines/CppCoreGuidelines) written by Bjarne Stroustrup himself.

## The Workhorses

Next is a set of languages that I will call the *workhorses*. These are languages that are very mature and widely used. In addition to C and C++, they power most of the software that our modern world runs on. But they are not flashy or sexy like some newer languages. Think of them like a trusted tool that you can pick up anytime with full confidence that it will get the job done.

### C#

[C#](https://dotnet.microsoft.com/en-us/languages/csharp) was developed by Microsoft and first appeared in 2000. The naming suggests it is their vision of what a "modern C" should be. It is part of the .NET family of languages and widely used in the Windows ecosystem. These days Microsoft actively supports Linux as well and C# works great under Linux. I have used C# extensively for many years in my professional work and also in some odd hobby projects. One example is my [Lunar Lander](https://github.com/peklaiho/lander) game written in C# using the [SDL2](https://www.libsdl.org/) library. By the way, I love SDL2, it is a great library and easy to use!

C# is an ok language and I don't mind writing it, but it's not quite there as one of my favorites. For me, something about it is just a bit off. There are some small things about the syntax and the default coding style that I do not particularly like. Another problem with the language is that it has evolved greatly over the years and there are multiple ways to do the same thing. Not a problem in a single-person project because then the coding style will be consistent. But in a team project, it can be difficult to enforce a consistent coding style. What ends up happening is that one person will write code in object-oriented style while another will write in functional programming style. This problem should plague other languages as well, but for some reason I always run into it in C# projects.

The [NuGet](https://www.nuget.org/) package repository is very good though. Packages are generally high quality, just what you would expect from a language and ecosystem geared towards enterprise users.

### Go

[Go](https://go.dev/) was developed by Robert Griesemer, Rob Pike, and Ken Thompson at Google and first appeared in 2009. It is a relatively simple language and the syntax is easy to read. Go is also known for first-class support of coroutines, useful for parallel programming in a controlled and safe way. Go has been gaining popularity in recent years and is particularly widely used in distributed systems. High-profile projects written in Go include [Docker](https://www.docker.com/), [Kubernetes](https://kubernetes.io/) and most of [HashiCorp](https://www.hashicorp.com/) products to name a few.

Go does not have a *try-catch* syntax popular in many other languages. Instead, the programmer must check the return value of every function call that may return an error, and then handle the error appropriately. This results in code with many `if err != nil` expressions. This kind of code is familiar to all Go developers. This is also one reason why some people do not like Go. But I actually like error handling in Go, because it forces the programmer to think about errors early on instead of ignoring them. In a *try-catch* language you can wake up to unexpected exceptions when your code is already running in production.

Personally I love Go and writing it feels very intuitive. It does exactly what I want, nothing more or less. It is not fancy but it *gets the job done* and gets out of your way. The documentation is good once you know your way around the language, but it is not very beginner-friendly. Though the [Tour of Go](https://go.dev/tour/) is a great introduction for beginners. I have used Go in multiple projects, though they have been smaller projects such as tools and utilities. Another really nice thing about Go is that it is easy to generate statically-linked native binaries. This means that you can compile a binary once and then just copy it to another system (or into a Docker container), and it will just work. This is useful when working with containers because the binary of an utility does not need to be built again with every release of the main application.

### Java

[Java](https://dev.java/) was developed by James Gosling at Sun Microsystems and first appeared in 1995. Now the Java ecosystem is owned by Oracle. Java was successful because it had syntax that was familiar to C/C++ developers. And object-oriented programming had gained enough popularity to become mainstream so it was accepted as the prevailing paradigm that Java conveniently offered. In my opinion Java had two distinctive features which contributed to its success. First was that the code was portable and could run on multiple platforms. Second was that it had strong support for creating graphical applications. Java code runs on the Java Virtual Machine (JVM). There are other languages that run on the JVM also, which we will get to later.

Java was the second language I learned after C, so I have a very long history with it. It was the primary languaged used to teach programming at my school when I was studying for my software engineering degree. That was around year 2005 or so. I also used it for some hobby projects when I was still learning the basics of programming. It was a big deal for me that I had a language to create graphical user interfaces with. So I used it to create some games and smaller applications. However, I have not used Java since that time. That says a lot about my preference for other languages.

I have also developed a dislike of using applications written in Java. In my experience, Java applications were always slow and consumed too much memory. This was particularly true of larger applications with many features. As an example, back in the day I tried some IDEs like [Eclipse](https://eclipseide.org/) and they were really sluggish. To be fair, that was a long time ago, and I am sure the performance of JVM has improved since then. But once you develop a consistently negative experience with a product or technology, that feeling tends to stick.

In case you are wondering, the name Java comes from coffee. Perhaps a brand Gosling preferred to drink while writing code. After all, coffee is a common beverage among programmers.

### JavaScript

JavaScript (commonly abbreviated JS) is so widespread nowadays that it almost needs no introduction. JavaScript first appeared in 1995 and initially it was exclusive to web browsers. Then [Node.js](https://nodejs.org/) came along and made it possible to run JavaScript on backend services as well. It has gained popularity in recent years because it is a relatively easy language for beginners to learn. Also thanks to Node.js, developers no longer needed to learn different languages for frontend and backend work. With the introduction of Node.js, every aspiring frontend developer could claim to be a full-stack developer almost overnight. This made the usage of JavaScript explode. As is to be expected, the quality of code generated by these aspiring backend developers was not so great initially.

Personally JavaScript is not one of my favorite languages. But I write JS whenever I work in a full-stack role though I prefer working strictly in the backend when possible. The syntax of JavaScript has improved a lot during the years. Now it has [classes](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Classes) and other features from more advanced languages. I would not use JavaScript for implementing backend services though. I think there are other languages that are just better for that.

The thing I most dislike about the JS/Node ecosystem is the [NPM](https://www.npmjs.com/) package manager, and the way projects tend to depend on too many third-party packages. After you start a fresh Node project, based on some popular web-framework for example, you will see NPM pull in lots of packages. After initializing a new project, take a look in the *node_modules* directory of your app. Often you will see hundreds upon hundreds of packages installed as dependencies. You just wanted to start a fresh [Angular](https://angular.dev/) or [React](https://react.dev/) app, but now you are stuck in a [dependency hell](https://en.wikipedia.org/wiki/Dependency_hell). Some of these third-party packages are written by not-so-experienced developers. Like all software, they contain bugs and security holes. Some of them will not be maintained because the maintainers get bored and move on to other projects.

### PHP

[PHP](https://www.php.net/) was developed by Rasmus Lerdorf and first appeared in 1995. It was intended primarily for building web applications. It became very successful because it was easy to use. PHP had simple C-like syntax and it was not strict about errors which made it ideal for beginner programmers to use. And programmers desperately needed a language to write dynamic websites instead of the static content that normal HTML provided. Today PHP powers many of the most popular websites in the world such as [Wikipedia](https://en.wikipedia.org/). PHP was also used extensively by [Facebook](https://www.facebook.com/). Some of the most prominent software written in PHP include [WordPress](https://wordpress.org/) and web frameworks such as [Symfony](https://symfony.com/) and [Laravel](https://laravel.com/).

PHP has been my go-to language for writing web applications for much of my professional life. So it is perhaps the language I have most experience with. PHP has a negative reputation in some minds because the permissive nature of the early versions of the language allowed many not-so-good developers write buggy code. But the language has been in active development and most of the early shortcomings have been fixed. It is also relatively fast for an interpreted language. So I would recommend that you give the "modern PHP" a try if you have not followed the language actively for many years. Another common misconception is that PHP is only suitable for web development. Actually PHP is a general-purpose language that is very well suited for writing shell scripts for example. I have written a guide about [using PHP on the command line](/blog/2022-03-php-command-line/).

My biggest criticism for PHP is the syntax that uses the same datatype, `array`, for two different things. An array in PHP can either be a sequential data type like a C-array that you index by integers, or a hash-map type of structure called *associative array* that you index by strings. These should have been two clearly separate datatypes, like they are in almost every other language. Obviously that cannot be changed now but I suspect the original PHP developers might consider this again if they could go back. The current implementation causes ambiguity in certain situations. For example, if you have an empty array, and you want to covert it into JSON, should it be `[]` or `{}`?

The package repository of PHP is [Packagist](https://packagist.org/). The packages are installed and managed by [Composer](https://getcomposer.org/). I have published two [public packages](https://packagist.org/packages/peklaiho/) so far and created over 50 internal packages used at work.

You are probably curious where the name *PHP* comes from. Their website states that it is a recursive acronym for *PHP: Hypertext Preprocessor*. But originally the technology was known as *PHP Tools* which came from *Personal Home Page Tools*. Rasmus originally wrote PHP for use on his personal website! It was the right tool at the right time and did not have much competition. Now PHP powers somewhere around 75% of all websites, depending on source. A large chunk of those websites use WordPress which is immensely popular. It is a great example of a technology that started small and took over the world, for better or worse.

### Python

[Python](https://www.python.org/) was developed by Guido van Rossum and first appeared in 1991. It has become very popular in the recent years, especially among beginners as an easy first language to learn. It is also the most popular language in the artificial intelligence / machine learning realm. Generally it is best suited for writing scripts and lightweight utilities, but cannot match the performance of compiled languages. Python is heavily used in academic settings thanks to interactive [Jupyter](https://jupyter.org/) notebooks and libraries such as [pandas](https://pandas.pydata.org/) and [NumPy](https://numpy.org/). Python also has a fairly popular web framework called [Django](https://www.djangoproject.com/).

Personally I do not really like Python. Part of that is the syntax that uses indentation for separation of code blocks instead of curly braces like many other languages. Also every method in a class has *self* as the first argument which feels unnecessary. But more importantly, since I do not work in machine learning or data analysis, I do not have any use-case for Python. For web I would rather use Laravel than Django. For small scripts I would default to PHP and then switch to Go if I need performance. Even though Python is not for me, I think it is a great first language for beginners to get started with. Most schools default to Python for teaching programming so that is another reason many students land on it. It is a simple and fun language to write. Use it to discover the *joy of programming*. But once you are adept with it, start looking at other languages as well.

## The Contenders

These next group of languages I am going to label as the *contenders*. They are not quite as prominent and widely used as the languages listed under the *workhorses* category. Some of them are older languages, like **Perl**, that used to be popular but have lost ground to other languages. Some are newer languages, like **Rust**, that are rising fast and gaining popularity. Some are niche languages, primarily used in a specific domain, but not widely used as general purpose languages. But all of these languages are solid and mature, ready to be used for serious projects.

Some of these languages I have not even had the chance to try yet.

### Erlang

[Erlang](https://www.erlang.org/) was developed at Ericsson by Joe Armstrong, Robert Virding and Mike Williams. The language first appeared in 1986. It was developed for use in telecommunication applications. The language design has heavy focus on concurrency and fault-tolerance. Erlang supports processes that are isolated and communicate with each other by passing messages. Designed for distributed systems, communication with a remote process works similarly as communication with a local process. This design is known as the [actor model](https://en.wikipedia.org/wiki/Actor_model). One prominent project written in Erlang is [RabbitMQ](https://www.rabbitmq.com/).

### Elixir

[Elixir](https://elixir-lang.org/) was developed by Jose Valim and first appeared in 2012. It runs on the Erlang VM so it shares many characteristics with Erlang. Elixir seems to be a "modern version" of Erlang with nicer syntax and perhaps more beginner-friendly. Because the languages run on the same VM, you can call Erlang functions from Elixir without overhead. [Phoenix Framework](https://www.phoenixframework.org/) is a web framework for Elixir.

### F#

[F#](https://fsharp.org/) was developed by Don Syme at Microsoft and first appeared in 2005. It runs on the .NET platform so it is a companion to C#. It is better suited for functional programming than its older brother. A project running on .NET can mix libraries written in C# and F#. The syntax is nice and code written in F# is much more concise than similar code written in C#. F# has the pipeline operator `|>` that allows you to chain functions similarly to pipes in Bash. Then you can write code with data flow from left-to-right instead of normal right-to-left.

I have tried F# on some small personal projects, but unlike C#, I have never used F# in a major project. But I like the syntax and would prefer to use F# over C# for my next .NET project.

### Haskell

[Haskell](https://www.haskell.org/) is a functional programming language that first appeared in 1990, but first stable release was in 2010. It is famous for advocacy of *pure functions* (functions that do not perform side-effects) and a little obscure syntax compared to most other languages. Haskell has many enthusiastic fans, but it has not gained widespread adoption because the syntax is so different for someone with a background in mainstream languages.

I have tried Haskell a little in the past, but did not get very far with it. Maybe because I am into Lisp, which also has obscure syntax compared to mainstream languages, so I can already scratch the itch of going off the beaten path without Haskell.

### Julia

[Julia](https://julialang.org/) was developed by Jeff Bezanson, Stefan Karpinski, Viral B. Shah and Alan Edelman. It first appeared in 2012. Julia uses the [multiple dispatch](https://en.wikipedia.org/wiki/Multiple_dispatch) style. Julia is also known for being a very fast language. Julia is heavily used in numerical analysis and computational science. Julia is also supported by [Jupyter](https://jupyter.org/).

Julia has the odd title of being my favorite language that I have never used. I have only heard good things about it and have always wanted to use it. Wait for me Julia, I will get to you one day, I promise!

### Kotlin

[Kotlin](https://kotlinlang.org/) was developed by [JetBrains](https://www.jetbrains.com/) and first appeared in 2011. It is a JVM-based language. The syntax of Kotlin is basically a more concise version of Java. Running on the JVM, Kotlin has full interoperability with Java, so all the Java libraries are usable. The IDEs that JetBrains is famous for such as *IntelliJ IDEA* are written mostly in Kotlin.

### Lua

[Lua](https://www.lua.org/) was developed by Roberto Ierusalimschy, Luiz Henrique de Figueiredo and Waldemar Celes. It first appeared in 1993. Lua is primarily known as an embedded scripting language that is used especially in games. A common pattern for games is that the main engine is written in C++, Lua is embedded, and then parts of the game logic are written in Lua. Usually this allows modders to develop mods using Lua as well.

I have personally used Lua quite a lot. Back when I was working with MUDs, I embedded Lua into a MUD engine written in C. I considered it a major accomplishment at the time because I was just a beginner in programming back then! I have also developed some [simple mods](https://github.com/peklaiho/game-mods) for games using Lua. These mods were mostly for personal use with small gameplay tweaks. I also developed a mod for *World of Warcraft* when I was actively playing it but sadly it seems I have lost the source code for it. I have also developed some game prototypes using [LÖVE](https://www.love2d.org/) which is a beginner-friendly game engine for Lua.

### OCaml

[OCaml](https://ocaml.org/) was developed by Xavier Leroy and others at INRIA in France. It first appeared in 1996. OCaml is based on the Caml dialect of [ML](https://en.wikipedia.org/wiki/ML_%28programming_language%29). It is a compiled language with fairly good performance and emphasis on functional programming. OCaml has a package manager called [opam](https://opam.ocaml.org/). I tried to find well-known software written in OCaml, but could not find any. [Jane Street](https://blog.janestreet.com/), a large company operating in the financial markets, came up as using OCaml extensively.

### Perl

[Perl](https://www.perl.org/) was created by [Larry Wall](http://www.wall.org/~larry/) and first appeared in 1987. Perl is used extensively as a scripting language for shell scripts. Perl is famous for string manipulation and regular expressions. The current version of Perl is 5. What was originally known as Perl 6 was split into a separate language called [Raku](https://raku.org/) in 2019.

I tried to learn Perl a while back by doing [Advent of Code](https://adventofcode.com/) puzzles. For me *Advent of Code* is a nice way to try new languages. I have to say that I did not like Perl. If I need to write a small script for string processing or JSON processing, and not care about performance too much, I already have PHP for that which I am familiar with. So for me personally, Perl does not bring much on the table. Also the syntax feels a little dated, which is understandable because the language is old. The most confusing thing for me was that Perl has something called *scalar context* and *list context*. Apparently similar things behave differently depending on which context you are in.

### R

[R](https://www.r-project.org/) language was created by Ross Ihaka and Robert Gentleman. It first appeared in 1993. R is primarily used in data analysis and statistical computing. It competes in this space mainly with Python and Julia.

### Ruby

[Ruby](https://www.ruby-lang.org/) was created by [Yukihiro Matsumoto](https://github.com/matz) and first appeared in 1995. Ruby is primarily known for the [Ruby on Rails](https://rubyonrails.org/) web framework, which may be more famous than even the language itself. *Ruby on Rails* was the first web framework to introduce the Model-View-Controller (MVC) pattern that many other frameworks subsequently copied. Of course there is also lots of other software written in Ruby.

I created a *Ruby on Rails* app once to try it out but that was a long time ago. I remember that the syntax of Ruby was nice, but one thing I disliked was that there was some ambiguity with the syntax. It felt like there was a couple of different ways of doing something, and it was not clear which one was the correct one. But that may have been more about my inexperience with the language rather than actual issue with the syntax. These days I would rather use [Laravel](https://laravel.com/) than *Ruby on Rails* as my preferred web framework.

### Rust

[Rust](https://www.rust-lang.org/) is a relatively new language and first appeared in 2015. It has gained a lot of popularity in recent years. Rust is most famous for the claim that it produces "safe" code. Memory safety in particular, meaning the compiler tracks that all allocated memory is also freed. This is an important feature that often leads to problems in programs written in C, where the developer has to track allocations and free operations. Rust also produces very fast code, comparable in performance to C and C++. Rust uses the *cargo* package manager and [crates.io](https://crates.io/) contains many packages to use. Yeah, packages are actually called *crates* in the Rust world. Rust has been on track to be included in the Linux kernel but it has received some push-back from existing Kernel developers who prefer to work with C.

Sadly I have not used Rust yet, but it has been on the top of my list of languages to learn for a long time. Hopefully soon, after I am done with Zig.

### Scala

[Scala](https://www.scala-lang.org/) is an interesting language. It was designed by Martin Odersky and first appeared in 2004. It is like a Java++ (improved Java) in my opinion. Like Java, it runs on the JVM but the syntax is more concise and the language is better suited for functional programming. But you still have the Java ecosystem to lean back on with thousands of libraries to choose from. Another good analogy to the .NET ecosystem is that Scala is like F# if Java is like C#. There are probably many famous projects written in Scala, but the one that comes to mind now is [https://lichess.org/](Lichess). Lichess runs very smoothly which is a good indication that Scala code can be fast if written by a good programmer.

I have written some small snippets in Scala to try it out, but have not used it in a major project. Scala is my second-favorite JVM-based language after Clojure.

### TypeScript

[TypeScript](https://www.typescriptlang.org/) was created by Microsoft and first appeared in 2012. It is an improved version of JavaScript, or more like JavaScript with types. However, the types are a little misleading because typically a TypeScript program is compiled down to JavaScript and the types are removed. So the types are checked during compile-time but not during runtime. This can still be a huge benefit and allows programmers to catch many bugs during compile-time that would not be caught with JavaScript. TypeScript has seen a huge increase in popularity during the last few years and it is increasingly replacing JavaScript in new projects. One example of an app written in TypeScript is the [Visual Studio Code](https://code.visualstudio.com/) editor.

Personally, I have mixed feelings about TypeScript. In most cases I prefer JavaScript because the code is much simpler and more concise. This is especially true in smaller projects with only a few developers, because it is easier to keep track of your codebase without strict type-checking by the compiler. TypeScript code is much more verbose, especially if you want to be pedantic about using types, which you should be because that is the point of using TypeScript in the first place. But in a larger project with lots of developers working together, I can definitely see how strict typing starts to bring more benefits.

I have used TypeScript extensively while working on a booking system app that is based on the [Angular](https://angular.dev/) framework. Currently I am writing the next version of the booking system using [Vue.js](https://vuejs.org/) with plain JavaScript. Based on these experiences I can say that the later is much more fun than the former and I am also making progress much more rapidly.

## Lisp

[Lisp](https://en.wikipedia.org/wiki/Lisp_%28programming_language%29) is the oldest language that I have included in this post. Yes, it is much older than C! The first version of Lisp was developed by John McCarthy. He started working on it in 1958. The name comes from *list processor*. The syntax of Lisp looks very different from other programming languages. Lisp code is written as [s-expressions](https://en.wikipedia.org/wiki/S-expression) which are parenthesized lists. Lisp coders love their parenthesis, they can never have enough of them!

I love Lisp. I have in fact created my own Lisp language called [MadLisp](https://madlisp.com/). I hope that is enough justification to put all Lisp-style languages in their own category here.

### Clojure

[Clojure](https://clojure.org/) was developed by Rich Hickey and first appeared in 2007. Clojure is arguably the most successful Lisp language because it used by many companies in real commercial projects. The other Lisp languages tend to be used more by hobbyists and people in academic settings (programming language research and so forth). Clojure runs on the JVM so you can freely interact with Java functions and libraries just like Scala. Obviously the performance has to be pretty good also since it is used in multiple serious projects.

Related to Clojure, there is also a language called [ClojureScript](https://clojurescript.org/). ClojureScript is compiled to JavaScript so it is ideal for Lisp lovers who want to write Lisp instead of JavaScript for web projects.

Cognitect, the company behind Clojure, was acquired by [Nubank](https://nubank.com.br/en/) in 2020. I am sure they offered Rich some crazy amount of money for it. But selling out to a Brazilian bank has a bit of a foul smell to it, no matter how you try to spin it. It goes a little against the ethos of the software community, if there is such a thing. Don't get me wrong, I am a capitalist, and might have made the same choice in that situation. But it is a little like getting caught with your hand in the cookie jar: Yes, we know why you did it. You love cookies. But you are still a naughty boy.

That being said, I do not like to mix technology with politics. For the most part, technology is neutral and should be kept as such. Being a Lisp lover, Clojure is definitely one of my favorite languages. I have not used it professionally yet, but would love to do so in the future.

### Common Lisp

[Common Lisp](https://common-lisp.net/) first appeared in 1984. Unfortunately I don't know much about it because it is the one Lisp that I have never used. For some reason I always preferred Scheme.

### Emacs Lisp

Emacs Lisp is the extension language of the famous [Emacs](https://www.gnu.org/software/emacs/) text editor. Actually to be more specific, Emacs is first a Lisp interpreter, and secondarily a text editor. This has the appeal of extreme customization: your text editor is actually an interactive programming environment, where every keystroke calls a function, and that function can be defined by you to perform infinitely complex logic. By the way, I believe this extreme customization is the *main reason* why Emacs is so successful. If it was just a text editor, we would have replaced it long ago with a better one.

A good example of my Emacs Lisp code would be my customized keyboard layout: the [omega-keys](https://github.com/peklaiho/omega-keys) package. If you like the modal editing style of Vim, but the default Vim keys feel a little awkward, give it a try!

### Scheme

[Scheme](https://www.scheme.org/) was developed by Guy L. Steele and Gerald Jay Sussman at MIT and first appeared in 1975. Unlike other languages here, Scheme does not have a single official implementation. There is just the standard for the language and there are many competing implementations. Though all implementations basically implement the same language, they have tiny differences. That is why Scheme has been called the most unportable programming language in the world.

The website lists [22 implementations](https://get.scheme.org/) of Scheme as I am writing this post. This abundance of choice can be paralyzing for a beginner because how do you know which one to choose? I will give a recommendations based on my experience. First, there is [Chez Scheme](https://cisco.github.io/ChezScheme/), currently owned by Cisco. It is generally ranked as the fastest Scheme implementation in [benchmarks](https://ecraven.github.io/r7rs-benchmarks/). So if you care about performance, Chez is a good place to start. Another good one is [GNU Guile](https://www.gnu.org/software/guile/). Guile is the official extension language of GNU so it is used by many GNU projects. It is not as fast as Chez, but has great documentation and standard library. It also has very good interoperability with C, so it is perfect for embedding Scheme in a C program. Finally there is [Racket](https://racket-lang.org/) which is designed as a *language to define new languages*. It is best suited for creating new domain-specific languages. Racket would also be good choice for teaching programming because you can draw 2D images with it which can be more appealing than textual output.

I have to also recommend the great book [The Little Schemer](http://mitpress.mit.edu/9780262560993/the-little-schemer/). That book paired with Scheme is about the most fun you can have on a computer with your pants on. It is perhaps the best resource for learning functional programming and thinking in Lisp.

## Off The Beaten Path

These next languages are either very new or not widely used. Try them out on some [LeetCode](https://leetcode.com/) puzzles for fun. But they may not be good enough for use in projects where stability and mature ecosystem are essential.

### Nim

Nim was developed by Andreas Rumpf and first appeared in 2008. Version 1.0 was published in 2019. As I understand it, Nim compiles to C code first, and then uses a normal C-compiler to create an executable in native machine code. The syntax of Nim is very similar to Python, so you could think of it as an improved version of Python. Or maybe "compiled Python" might be more accurate. Nim would probably be a good language for someone who loves Python but wants improved performance.

### Odin

[Odin](https://odin-lang.org/) is a new programming language being developed by Ginger Bill. Odin is a low-level language and is marketed as a "modern C". It is still in the very early stages and there is only a development build available for download as I am writing this. Odin is used in production by [JangaFX](https://jangafx.com/) for 3D animation software.

### V

[V](https://vlang.io/) language is being developed by Alexander Medvednikov and first appeared in 2019. It is still under development and currently available as version 0.4. V is mostly an improved version of Go. The syntax is very similar to Go but also contains many improvements. The infamous `err != nil` checks are not needed and data is immutable by default. V has optional garbage collection so manual memory management is possible if desired. It also places emphasis on fast compilation and strong interoperability with C.

V looks really interesting from my point of view because I love Go already. It is definitely high on my list of languages to try. But for some reason there is not much buzz around it on the Interweb. I read [Hacker News](https://news.ycombinator.com/) fairly regularly, but do not remember seeing V language come up much. I guess the developers are busy writing code instead of marketing, which is a *good thing*.

### Zig

[Zig](https://ziglang.org/) language is being developed by Andrew Kelley and first appeared in 2016. It is under active development and the current available version is 0.13. Zig is mainly marketed as the "modern C". Some other mentioned languages also market themselves as the "modern C", so it seems like there is a bit of trend going on here. Zig has no hidden control flow or hidden memory allocations. For example, a statement like `a + b` could call a function in C++ because of operator overloading, but not in Zig. Every function that allocates memory in Zig takes an allocator as an argument. So the programmer must make a decision which allocator to use every time. Zig has different compilation modes that allow the programmer to choose the balance between safety and speed.

The language is intentionally minimal and tries to have only one way to do something. Zig does not support object-oriented programming so it only has structs like C, but not classes. On the other hand, structs can have functions, so they are kind of like methods. Wait a minute, that is OOP all over again! I hoped that by using Zig I could finally escape the sticky tentacles of OOP that seem to have a hold on me! On closer inspection they are apparently just "normal functions" that you can call with the dot-syntax for convenience. Just please do not add classes and inheritance in Zig, I beg of you Andrew!

On a more serious note, Zig is the language that I am currently most excited about. I intend to give learning Zig a somewhat serious effort if I am not distracted by other things too much. Keeping fingers crossed!

## Which Language Should You Learn?

There are so many interesting languages to choose from! We modern software developers have it so good. Think about the old-timers who had to choose between C and C++ (and maybe Java a little later). But how do you know which one you should be learning?

The first step is to learn the basics of programming. In theory you could do that with any language, but some are better suited than others. **Python** has become the industry default for new programmers because it is simple and beginner-friendly. I also think Python is the perfect language for beginners.

After learning the basics with Python, choosing the next language depends on what area you want to specialize in. Different languages are used in different fields. Here are some recommendations:

* If you want to work in machine learning, stay with **Python**.
* If you want to work in data analysis, stay with **Python** also, but look into **R** or **Julia**. You also need to be good with **SQL**.
* If you want to work in game development, learn **C++** and **Lua**.
* If you want to work in frontend web development, learn **JavaScript** and **TypeScript**.
* If you want to work in backend web development, learn **PHP**.
* If you want to work in the Microsoft/Windows ecosystem, learn **C#** and **F#**.
* If you want to work in the Java ecosystem, learn **Java** first, then look into **Scala** or **Kotlin**.
* If you want to work in the GNU/Linux ecosystem, learn **C** and **Bash**.
* If you want to work with distributed systems or Docker/Kubernetes, learn **Go**.
* If you want to work with a more cutting-edge language, learn **Rust**.

These are the languages that will pay your bills. For hobby projects, use something goofy like **Lisp** or **Haskell**!
