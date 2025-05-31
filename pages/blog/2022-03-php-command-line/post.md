---
title: "PHP on the Command Line"
date: "2022-03-27"
tags: [php, software-engineering]
---

Most people tend to associate [PHP](https://www.php.net/) with web programming. They might be unaware that PHP is actually great language for shell scripts and command-line use as well. The syntax is relatively expressive so you can do a lot in just a few lines of code. And like [Bash](https://www.gnu.org/software/bash/), you can just write a script and run it. This is ideal for quickly writing small scripts and leads to faster development than compiled or strictly-typed languages like [Go](https://go.dev/) and C#. PHP is also [faster](https://benchmarksgame-team.pages.debian.net/benchmarksgame/fastest/php-python3.html) than [Python](https://www.python.org/) which is apparently commonly used for shell scripting.

So I decided to write a blog post about getting started with PHP on the command line. Perhaps this would be helpful for someone who is already using PHP for web development but does not have much experience with shell scripts yet. I will try to keep this relative simple so it would be accessible for beginner-level developers.

The first step is to get PHP installed on your system. I will use Linux (and Bash) here for examples but everything should work on Mac as well. For Windows some modifications may be needed but of course PHP [runs on Windows](https://windows.php.net/) likewise. Generally installing a package named **php** is all you need on mainline Linux distributions like Ubuntu or Arch.

The executable is called `php` and should be in your *$PATH* automatically. *$PATH* is an environment variable that contains a list of directories that Bash uses to find executable commands.

You can run `php` with the `--version` option on the command line to make sure everything is working:

```
$ php --version
PHP 8.1.4 (cli) (built: Mar 16 2022 11:32:47) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.4, Copyright (c) Zend Technologies
    with Zend OPcache v8.1.4, Copyright (c), by Zend Technologies
```

## The REPL

The first useful thing for working in the command line is the *REPL*, short for *Read-Eval-Print-Loop*, an interactive programming environment where you can type commands one at a time. This is helpful when you want to quickly test how some short piece of code works, like a built-in function. I often jump quickly into the REPL even during web development to test something. You can start it with the `-a` option and then just start writing code:

```
$ php -a
Interactive shell

php > $cities = ['new york', 'paris', 'london'];
php > print_r(array_map('ucfirst', $cities));
Array
(
    [0] => New york
    [1] => Paris
    [2] => London
)
```

You can exit the *REPL* by typing `exit` or pressing <kbd>Ctrl+c</kbd> or <kbd>Ctrl+d</kbd>.

## Executing a File

To execute a PHP file just give it as a parameter to the `php` command:

```
$ cat hello.php
<?php
echo "Hello, PHP!\n";

$ php hello.php
Hello, PHP!
```

Note that you still need to use the `<?php` tag in command line scripts as well.

## Arguments

Of course it is necessary to be able to give arguments to your scripts from the command line. PHP provides two special variables to access the arguments from within the script. The [$argv](https://www.php.net/manual/en/reserved.variables.argv.php) variable is an array of given arguments and the [$argc](https://www.php.net/manual/en/reserved.variables.argc.php) variable is the length of the array. These names are commonly used in C programs as well.

The first item of the array is the name of the script itself. So the actual arguments start from the second item. Here is a simple script to demonstrate:

```
$ cat args.php
<?php
echo "Count: $argc\n";

echo "Values:\n";

for ($i = 0; $i < $argc; $i++) {
    echo "$i: $argv[$i]\n";
}
```

And running it:

```
$ php args.php abc efg
Count: 3
Values:
0: args.php
1: abc
2: efg
```

For advanced parsing of command line arguments, take a look at the [getopt](https://www.php.net/manual/en/function.getopt) function.

## Exit Codes

Shell scripts use integer-based exit codes to indicate if they executed successfully or if an error occured. The exit code of 0 (zero) indicates success and any non-zero exit code indicates error. This may feel strange at first because of some intuition to return a truthy boolean value (1) to indicate success and a falsy boolean value (0) to indicate failure.

Use the [exit](https://www.php.net/manual/en/function.exit) function to terminate the script with the correct exit code. Note that in Bash you can use the special variable `$?` to read the exit code of the last command. Consider the following example of script that fails with an error:

```
$ cat error.php
<?php
echo "Error!\n";
exit(1);

$ php error.php
Error!

$ echo $?
1
```

**Warning:** The `exit` function has a quirk that it also accepts a string argument. In this case it prints the string and exits with an exit code of 0. I find this very ambiguous and confusing. Therefore I recommend using [echo](https://www.php.net/manual/en/function.echo) for output and `exit` strictly for exit codes.

## Standard Input/Output

In addition to command line arguments and exit codes, many practical scripts will need to utilize the standard input/output streams. This will allow your scripts to be used in Bash pipes to create more complex commands from simple individual scripts.

The [standard I/O streams](https://www.php.net/manual/en/features.commandline.io-streams.php) `STDIN`, `STDOUT` and `STDERR` are opened for you automatically so no call to [fopen](https://www.php.net/manual/en/function.fopen) is needed.

Lets make a simple script to read from `STDIN`, transform the data somehow, and write it to `STDOUT`:

```
$ cat upcase.php
<?php
while (!feof(STDIN)) {
    $input = fread(STDIN, 128);
    if ($input !== false) {
        $output = strtoupper($input);
        fwrite(STDOUT, $output);
    }
}
```

And then run it by piping a text file to it:

```
$ cat some_text.txt
lets try if php can make this
text uppercase or if something
weird will happen like the cpu
will explode?

$ cat some_text.txt | php upcase.php
LETS TRY IF PHP CAN MAKE THIS
TEXT UPPERCASE OR IF SOMETHING
WEIRD WILL HAPPEN LIKE THE CPU
WILL EXPLODE?
```

Pretty awesome! Scripts that read input, transform it, and then output it are called *filters*. They are quite useful.

## Environment Variables

Shell scripts commonly need to read *environment variables*. In addition to arguments and input streams, this is another method of passing information into your script.

You can read environment variables using the [getenv](https://www.php.net/manual/en/function.getenv) function:

```
$ cat env.php
<?php
echo 'Environment is ' . getenv('MYVAR') . "!\n";

$ export MYVAR=delicious

$ php env.php
Environment is delicious!
```

## Scripts as Commands

The examples so far have required us to explicitly prefix the script name with `php` in order to run it. This works quite well, but it would be more convenient and practical to be able to create scripts as commands where we write only the command name to execute it.

This is accomplished in Bash by adding a special [shebang](https://en.wikipedia.org/wiki/Shebang_%28Unix%29) notation as the first line of the file. For PHP scripts it looks like this:

```
#!/usr/bin/env php
```

Basically it just tells Bash which program to use to evaluate the rest of the file. The standard convention is to refer to `/usr/bin/env` instead of the PHP executable directly. This makes the script more robust because it does not depend on a particular location of the PHP executable which could be different under different operating systems.

Lets create a simple command, this time also leaving out the `.php` file extension, to test the amazing *shebang*:

```
$ cat my-cmd
#!/usr/bin/env php
<?php
echo "Hello, My-Command!\n";
```

The important new step is to add the executable bit using `chmod` so we have permission to execute the script directly:

```
$ chmod a+x my-cmd
```

And now we can run it, without prefixing it with `php`:

```
$ ./my-cmd
Hello, My-Command!
```

The `./` prefix is needed assuming the current directory is not included in the *$PATH* variable that Bash uses to find commands. If you put the script in a directory that is included in *$PATH*, you can run the command from any directory by only typing `my-cmd`.

I suggest creating a dedicated directory for all of your scripts that you want to use as commands and then including it in *$PATH*. This allows you to have a collection of handy custom commands for often-repeated tasks. Of course there is no need to write all of them in PHP since you can easily define commands in different languages using the *shebang* notation.

## Standard Library

PHP has an extensive collection of built-in functions for common tasks performed in shell scripts. I will list some of the most handy ones here.

Of course the *array* is the *bread and butter* of PHP scripts. There is rarely a script that does not use a collection of objects of some kind, either a list or a key-value map. The [array functions](https://www.php.net/manual/en/ref.array.php) provide everything that is needed for working with arrays such as searching and sorting.

Processing text and strings is another very common use case of scripting. The [string functions](https://www.php.net/manual/en/ref.strings.php) provide ways to work with strings. [Regular expressions](https://www.php.net/manual/en/ref.pcre.php) are useful when you need to search or replace more advanced patterns. If you need to work with Unicode (multi-byte) strings, take a look at the [mbstring](https://www.php.net/manual/en/ref.mbstring.php) functions.

Another common functionality in shell scripts is to deal with files and directories. The [filesystem functions](https://www.php.net/manual/en/ref.filesystem.php) and [directory functions](https://www.php.net/manual/en/ref.dir.php) provide tools for dealing with files and directories. Everything that is needed to work with files such as listing, reading, writing, copying and deleting them is there. For quickly reading and writing files with a single function, the [file_get_contents](https://www.php.net/manual/en/function.file-get-contents.php) and [file_put_contents](https://www.php.net/manual/en/function.file-put-contents.php) are particularly useful. The [glob](https://www.php.net/manual/en/function.glob.php) function is great for listing files that match a specific pattern.

If you need to prompt the user for some input during the script, use [readline](https://www.php.net/manual/en/ref.readline.php) for that.

Finally, shell scripts often need to start and execute other shell commands. Multiple [program execution functions](https://www.php.net/manual/en/ref.exec.php) are provided for that purpose such as [exec](https://www.php.net/manual/en/function.exec.php) and [system](https://www.php.net/manual/en/function.system.php).

## Syntax Checking (Linting)

Another useful feature of the PHP executable is the built-in syntax checker (linter). It checks a PHP file for syntax errors. Lets make a simple script with an error to test it:

```
$ cat syntax.php
<?php
// Oh dear, the programmer forgot a semicolon!
echo "Hello, Syntax!"
```

Syntax checking is done by giving the `-l` option to the command:

```
$ php -l syntax.php
PHP Parse error:  syntax error, unexpected end of file, expecting "," or ";" in syntax.php on line 4

Parse error: syntax error, unexpected end of file, expecting "," or ";" in syntax.php on line 4
Errors parsing syntax.php
```

Of course we can also verify that it returned a non-zero exit code to indicate error:

```
$ echo $?
255
```

## Built-in Web Server

The PHP executable also includes a light-weight web server that is suitable for use during development. This allows you to do local web development without installing a fully-featured web server like [Apache](https://httpd.apache.org/) or [nginx](https://nginx.org/).

The web server is started with the `-S` option followed by IP address and port number. For example:

```
$ php -S 127.0.0.1:8000
[Sun Mar 27 08:52:17 2022] PHP 8.1.4 Development Server (http://127.0.0.1:8000) started
```

Now you can access the server by navigating to **http://localhost:8000/** with your web browser.

The *document root* is set to the current directory where the server is started. You can also give the `-t` option to specify another directory as the *document root*:

```
$ php -S 127.0.0.1:8000 -t mysite/public/
[Sun Mar 27 08:56:16 2022] PHP 8.1.4 Development Server (http://127.0.0.1:8000) started
```

## Other Tips

Here is a few other tips that are important to know when working with command line scripts.

### Current Working Directory

When running shell commands, generally the user wants to do something in the *current working directory* (the directory that the command was invoked from), not in the directory that the script itself is located.

For this purpose PHP provides the [getcwd](https://www.php.net/manual/en/function.getcwd.php) function that returns the *current working directory (cwd)*. Here is an example of it in action:

```
$ cat cwd.php
<?php
echo 'Current Working Directory: ' . getcwd() . "\n";

$ php cwd.php
Current Working Directory: /home/pekka

$ cd /etc

$ php ~/cwd.php
Current Working Directory: /etc
```

### SAPI Name

There may be cases where you need to know inside your script if it is being executed from the command line or through a web server. The [php_sapi_name](https://www.php.net/manual/en/function.php-sapi-name.php) function is provided for this purpose. It will return the string `cli` when a script is being executed from the command line:

```
$ cat sapi.php
<?php
echo 'I am being executed by: ' . php_sapi_name() . "\n";

$ php sapi.php
I am being executed by: cli
```

### Magic Constants

Sometimes you need to know the full filename or directory of the script from inside itself. You should avoid hard-coding this information inside your scripts because they could be moved to another directory in the future and that could break the hard-coded references.

PHP provides [magic constants](https://www.php.net/manual/en/language.constants.magic.php) for this purpose. `__FILE__` returns the full filename of the script and `__DIR__` returns the directory:

```
$ cat whoami.php
<?php
echo 'I am: ' . __FILE__ . "\n";
echo 'In directory: ' . __DIR__ . "\n";

$ php whoami.php
I am: /home/pekka/tmp/blog/whoami.php
In directory: /home/pekka/tmp/blog
```

When referring to other files and directories relative to the current one, it is good practice to use `__DIR__`:

```
$ cat first.php
<?php
require __DIR__ . '/second.php';

$ cat second.php
<?php
echo "Hello, Second!\n";

$ php first.php
Hello, Second!
```

This way relative references are expanded to absolute directory names. They will always work, regardless of the current *working directory* where the first script is being executed from, and they refer unambiguously to the exact file that you want.

### Third-Party Libraries

If you are writing something more complex than a simple script, it might be useful to take a look at available third-party libraries for developing console apps. There are plenty of them on [Packagist](https://packagist.org/) installable by [Composer](https://getcomposer.org/). I will avoid giving any specific recommendations here because what is working today might be deprecated tomorrow. Generally I would avoid creating dependencies on third-party libraries if they are not really required.

## Example Script

As an example, here is a small script that uses some of the things discussed in this post.

```php
#!/usr/bin/env php
<?php

// List changed files
exec('git status -s', $files);

// Filter file names
$fn = function ($f) {
    $parts = preg_split("/\s+/", $f, 2, PREG_SPLIT_NO_EMPTY);
    return $parts[1];
};
$files = array_map($fn, $files);

// Filter only php files
$fn = function ($f) { return substr($f, strlen($f) - 4) == '.php'; };
$files = array_filter($files, $fn);

// Check the syntax
$fn = function ($f) { system('php -l ' . $f); };
array_map($fn, $files);
```

The script runs `git status`, filters all the files ending with `.php`, and finally checks their syntax using `php -l`. It is useful to check for errors before a `git commit` for example and can even be run automatically in a [git hook](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) if preferred.


That's it for now! I hope this post gives some ideas and pointers about how to use PHP for more than just web development.
