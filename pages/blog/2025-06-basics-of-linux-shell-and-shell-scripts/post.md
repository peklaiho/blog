---
title: "Basics of Linux Shell and Shell Scripts"
date: "2025-06-15"
tags: [linux, software-engineering]
---

This post covers the basics of using the shell in Linux, how to write shell scripts, as well as some information about processes in general. I use the [Bash](https://www.gnu.org/software/bash/) shell so the syntax represented here reflects that. If you use another shell, the syntax might be slightly different, so consult the documentation. I should also point out that while I refer to a system as Linux for simplicity, I am of course talking about [GNU/Linux](https://www.gnu.org/gnu/linux-and-gnu.html). And this is relevant for BSD users as well!

A shell script written in the language of the shell itself (such as Bash) is a collection of shell commands that are executed one after another. In this sense a shell script is just a collection of normal commands you are typing on the terminal every day as a Linux user. On the other hand, you can write a shell script just as easily in another scripting language, such as Python, Perl or PHP. Most scripting languages can accomplish things that are commonly needed in shell scripts such as string manipulation, working with files, and so on. The choice of a scripting language then comes down to personal preference.

I have written before about [writing shell scripts using PHP](/blog/2022-03-php-command-line/) which is related.

## Strings

Bash supports both single-quoted and double-quoted strings. The difference is that variables starting with `$` inside double-quoted strings are expanded to their values. Single-quoted strings are kept unchanged.

```
$ echo 'Use the $SHELL variable to check what shell you are using'
Use the $SHELL variable to check what shell you are using
$ echo "My shell is $SHELL"
My shell is /usr/bin/bash
```

## Standard Input/Output Streams

Processes in Linux have three standard streams:

* Standard input, **STDIN**, file descriptor 0
* Standard output, **STDOUT**, file descriptor 1
* Standard error, **STDERR**, file descriptor 2

The standard streams are automatically managed for a process, so there is no need to explicitly open or close them.

When you are running a program interactively from the terminal, the standard input is connected to your keyboard. Any characters you type on the keyboard get passed on to the standard input of the running process. If the process writes to standard output or standard error, those characters are printed on the screen.

When writing data out from your program, it is important to distinguish between standard output and standard error. The standard output is meant for the primary data that a program produces. The standard error is meant for error messages, log messages, and similar data that is secondary to the main function of the program. A common mistake is to write everything, including log messages, to the standard output. This is done because it is more convenient (a simple `echo` or `print` command in most languages). But then you lose the ability to distinguish between primary and secondary data.

## Pipe Operator

The `|` (pipe) operator is the *holy grail* of working on the Linux shell because it is so powerful. The pipe operator allows two commands to be linked together, so that the *standard output* of the first command is connected to the *standard input* of the second command.

A simple example is sorting some lines of text:

```
$ echo -e "Omega\nBeta\nGamma\nAlpha" | sort
Alpha
Beta
Gamma
Omega
```

A more practical situation that occurs commonly is that you have a command that outputs some text, and then you need to search that text to find a particular line. This can be done by piping the output from the first command into `grep`.

Of course more than two commands can be connected in this way, forming a chain where each command performs some operation and then passes the data on to the next step. This is the *bread and butter* of being an efficient Linux user. Creating small and simple programs that are easily composable with each other is core to the [Unix Philosophy](https://en.wikipedia.org/wiki/Unix_philosophy).

## Basic Shell Script

The first line of a Linux shell script is usually the so-called [shebang](https://en.wikipedia.org/wiki/Shebang_%28Unix%29). It tells the shell which program should be used to execute the script. It starts with the characters `#!` followed by the absolute path of the interpreter.

Thus a basic Bash script looks like this:

```
#!/bin/bash
echo 'Hello, World!'
```

Note that a script must be made executable using `chmod` before it can be executed:

```
$ chmod a+x hello
$ ./hello
Hello, World!
```

Here are examples of similar scripts in common scripting languages **Python**, **Perl** and **PHP**:

```
#!/usr/bin/env python
print("Hello, World!")
```

```
#!/usr/bin/env perl
print "Hello, World!\n"
```

```
#!/usr/bin/env php
<?php
echo "Hello, World!\n";
```

The important part to note is that all of these have `/usr/bin/env` on the shebang line, followed by the name of the actual interpreter. This convention was adapted to make shell scripts more universal. The absolute path to the `python` executable for example could vary between different Linux distributions.

### Including Scripts in PATH

Normally you want your scripts to be discoverable and executable from any directory. You can do this by creating a dedicated directory for your scripts, such as `~/bin` or `~/scripts`, and then adding that directory to the special variable **PATH** in your `~/.bashrc` or `~/.bash_profile` configuration file.

Bash uses the directories listed in **PATH** to look up executable commands. The directories are separated by a colon.

For example, my **PATH** looks like this:

```
$ echo $PATH
/home/pekka/bin:/home/pekka/.mytools:/usr/local/sbin:/usr/local/bin:/usr/bin:/usr/bin/site_perl:/usr/bin/vendor_perl:/usr/bin/core_perl
```

## Exit Codes

Linux processes use integer based exit codes to indicate whether the program executed successfully or not. Zero indicates success and a non-zero exit code indicates error.

Lets create simple scripts `success` and `fail` to test how exit codes work:

```
#!/bin/bash
echo 'Success'
exit 0
```

```
#!/bin/bash
echo 'Fail'
exit 1
```

Bash has a special variable `$?` which stores the exit code of the last command. It can be used to check if the previous command executed successfully or not:

```
$ ./success
Success
$ echo $?
0
$ ./fail
Fail
$ echo $?
1
```

### Logical Operators (AND, OR)

Bash has logical operators that can be used together with exit codes.

The `&&` (AND) operator can be used to combine two commands so that the second is only executed if the first one succeeds.

```
$ ./success && ./fail
Success
Fail
$ ./fail && ./success
Fail
```

The `||` (OR) operator can be used to combine two commands so that the second is only executed if the first one fails.

```
$ ./success || ./fail
Success
$ ./fail || ./success
Fail
Success
```

## Command Line Arguments

Shell scripts support command line arguments, which is a common way to pass information or options to a script. The arguments are accessible inside the script by special variables starting with `$1` which contains the first argument. The second argument is in `$2` and so on.

Other special variables related to arguments:

* `$0` is the command which was used to execute the script
* `$#` is the number of arguments
* `$*` is all the arguments together, as in `"$1 $2"`
* `$@` is all the arguments individually, as in `"$1" "$2"`

Lets make a simple script to test how command line arguments work:

```
#!/bin/bash
echo "Command: $0"
echo "First argument: $1"
echo "Second argument: $2"
echo "Number of arguments: $#"
echo "All arguments as one: $*"
echo "All arguments individually: $@"
```

And test it with some arguments:

```
$ ./args Hello World!
Command: ./args
First argument: Hello
Second argument: World!
Number of arguments: 2
All arguments as one: Hello World!
All arguments individually: Hello World!
```

In many programming languages such as C and PHP, the convention is that argument count is represented as a variable named `argc` and argument values are in a variable named `argv`.

### Arguments from Standard Input

Sometimes you need to pass command line arguments from *standard input*. Using the normal pipe operator does not work for this. But Linux has a tool for this specific situation called `xargs`.

Here is an example of passing command line arguments to `ls` using `xargs`:

```
$ echo 'hello success fail' | xargs ls -l
-rwxr-xr-x 1 pekka pekka 31 Jun  8 08:51 fail
-rwxr-xr-x 1 pekka pekka 33 Jun  8 08:39 hello
-rwxr-xr-x 1 pekka pekka 34 Jun  8 08:51 success
```

Which is equivalent to running `ls -l hello success fail`. Obviously we did not need `xargs` here, this is just an example of how it works.

## Redirection

Because writing to and reading from files is so common, Bash provides redirection operators for these purposes.

The `>` operator is used to redirect the *standard output* into a file:

```
$ ls -l > filelist
```

If you run the command again the file is overwritten. Sometimes you want to append data to an existing file instead. Append is done with the `>>` operator:

```
$ ls -l >> filelist
```

A file can be read and sent to the *standard input* of a process with the `<` operator:

```
$ grep hello < filelist
-rwxr-xr-x 1 pekka pekka  33 2025-06-08 08:39 hello
```

This is roughly equivalent to reading a file with `cat` and using pipe, which may look more intuitive because data is flowing left-to-right:

```
$ cat filelist | grep hello
-rwxr-xr-x 1 pekka pekka  33 2025-06-08 08:39 hello
```

The above is just an example. There is no need to use `cat` here, because `grep` can read files directly. So to be pedantic, you should just write the above as `grep hello filelist`.

### Discarding Output

Sometimes you need to discard the output of a command. Linux has a special file `/dev/null` that can be used for this purpose. It is like a black hole that eats any bits that you send to it!

The black hole can be fed by redirecting output into it:

```
$ ls -l > /dev/null
```

### Redirecting Standard Error

So far we have been redirecting *standard output*, but not *standard error*. This is what you commonly want. But sometimes you want to redirect *standard error* as well.

Lets explore this by creating a simple script, in Python this time:

```
#!/usr/bin/env python
import sys
sys.stdout.write("Normal output\n")
sys.stderr.write("Error message\n")
```

It prints a message to both *stdout* and *stderr* if you run it:

```
$ ./stdout-stderr
Normal output
Error message
```

If you redirect the output into a file using the normal `>` operator, the error message is printed to console:

```
$ ./stdout-stderr > output
Error message
$ cat output
Normal output
```

If we want error messages to go into the same file, we can use the `&>` operator instead:

```
$ ./stdout-stderr &> output
$ cat output
Error message
Normal output
```

For appending an existing file, the `&>>` operator is available as well.

Of course you can also redirect errors into a separate file:

```
$ ./stdout-stderr > output 2> errors
$ cat output
Normal output
$ cat errors
Error message
```

### Tee Command

Sometimes you want to write to *standard output* but also write the same output into a file. Linux has a `tee` command for this purpose:

```
$ echo 'Hello, World!' | tee output
Hello, World!
$ cat output
Hello, World!
```

The `tee` command can be thought of as a T-shaped junction, sending its input two ways.

## Environment Variables

Environment variables are another way of passing data into a program. The naming convention for environment variables is to use all uppercase characters and separate words by underscores.

There are some standard environment variables already defined in a default shell session:

```
$ echo "My home is: $HOME"
My home is: /home/pekka
$ echo "My shell is $SHELL and my terminal type is $TERM"
My shell is /usr/bin/bash and my terminal type is st-256color
```

You can run the `env` command to see all the environment variables that have been defined in your current session.

New variables are created by using a simple `NAME=value` syntax:

```
$ MYJOB=Hacker
$ echo "My profession: $MYJOB"
My profession: Hacker
```

This is called a *shell variable* and it is usable in the current shell session. But it is not a true environment variable yet, because it is not usable in shell scripts or other sub-processes. Lets write the above echo command as a shell script and run it:

```
$ cat test-env
#!/bin/bash
echo "My profession: $MYJOB"
$ ./test-env
My profession:
```

To make a variable visible to sub-processes, we have to use the `export` command:

```
$ export MYJOB
$ ./test-env
My profession: Hacker
```

Now it works! Generally when setting an environment variable the `export` command is written on the same line:

```
$ export MYJOB=Hacker
```

Another way is to write the variables in front of the command to be executed. This way the variables are only set for that particular command:

```
$ MYJOB='Secret Agent' ./test-env
My profession: Secret Agent
$ echo $MYJOB
Hacker
```

## Process IDs

Every Linux process has a process ID (commonly called **PID**). The PID is an integer that uniquely identifies a process. Bash has special variables for accessing the PIDs of processes.

The `$$` variable returns the PID of the current process. Lets write a small script to test it:

```
#!/bin/bash
echo "My PID is $$"
```

The PID changes every time we run it:

```
$ ./pid
My PID is 85753
$ ./pid
My PID is 86003
```

Another special variable is `$!` which returns the PID of the last background process. Background jobs are explained below in more detail.

## Signals

Signals are yet another way to pass information into a Linux process. Their most common use is to stop a running process.

To test signals, lets create a sample script that prints its PID and runs forever:

```
#!/bin/bash
echo "Sleeper running, PID $$"
while true; do
    sleep 1
done
```

This script will run forever unless stopped by the user. Stopping a running program is generally done by pressing <kbd>Ctrl</kbd> + <kbd>C</kbd> as shown here:

```
$ ./sleeper
Sleeper running, PID 88054
^C
$
```

Behind the scenes this key combination sends a **SIGINT** (interrupt) signal to the process. This causes the process to interrupt whatever it is doing.

Another way to send a signal to a process is the `kill` command. As the name suggests, it is normally used to kill processes. By default it sends a **SIGTERM** (terminate) signal to a process.

Lets test the `kill` command. First run the sleeper again:

```
$ ./sleeper
Sleeper running, PID 89974
```

Then run in another terminal (using the same PID):

```
$ kill 89974
```

The sleeper process should terminate.

If you have a persistent process that does not want to die, you can send a **SIGKILL** (number 9) signal with the command `kill -9 <pid>`. This forcefully terminates a process, skipping any cleanup operations the process might normally perform before exiting.

### Custom Signals

In addition to signals discussed above, there are two special signals **SIGUSR1** and **SIGUSR2** that are reserved for custom uses. Each process can choose what to do (if anything) when they receive these signals. They can be used for custom application logic. For example, they are commonly used to reload a configuration file of a daemon process.

Signal handlers in Bash are registered with the `trap` command. By the way, you can print a list of all signals with `trap -l`.

Lets add a signal handler to the sleeper script:

```
#!/bin/bash
echo "Sleeper running, PID $$"
while true; do
    sleep 1
done
$ nano sleeper
$ cat sleeper
#!/bin/bash
echo "Sleeper running, PID $$"

handle_sigusr1() {
    echo "Received SIGUSR1 signal!"
}

trap handle_sigusr1 SIGUSR1

while true; do
    sleep 1
done
```

Run the sleeper script and then send a **SIGUSR1** signal from another terminal using the PID from the script:

```
$ kill -USR1 11182
```

You should see *"Received SIGUSR1 signal!"* printed from the script.

## Background Jobs

Normally when you run a command in a Bash session, it takes over your terminal and you are unable to start other commands while the first one is running. When this happens the process is running in *foreground*. Bash also has the concept of *background* jobs which keep running in the background, freeing you to run other commands meanwhile.

We can use the *sleeper* script from the previous paragraph to test this functionality, but we will comment out the part where the script prints its own PID. Bash does this automatically when starting a background job.

To start a process in the background, just add the `&` symbol at the end of the command:

```
$ ./sleeper &
[1] 24521
$
```

Now the script is running in the background, but control is returned to the terminal, allowing you to type other commands. The number in `[1]` is the job ID, and the number 24521 is PID.

Use the `jobs` command to see a list of background jobs and their statuses:

```
$ jobs
[1]+  Running                 ./sleeper &
```

You can change a background process into a foreground process with the `fg` command. Job numbers are prefixed with the `%` character:

```
$ fg %1
./sleeper
```

If a process is running in the foreground, you can suspend it and send it to the background by pressing <kbd>Ctrl</kbd> and <kbd>Z</kbd>:

```
$ ./sleeper
^Z
[1]+  Stopped                 ./sleeper
$
```

As Bash informs us, the status is Stopped. You can resume a suspended job with the `bg` command:

```
$ bg %1
[1]+ ./sleeper &
$
```

For convenience, the `kill` command also takes a job ID instead of PID:

```
$ kill %1
[1]+  Terminated              ./sleeper
```

## More Information

This post is pretty long, so I have to cut it here. That being said, it only covers the very basics of Bash and Linux processes. For more information, I recommend reading the Bash manual (`man bash`). The manual is also available as a [PDF file](https://www.gnu.org/software/bash/manual/bash.pdf).

Another excellent resource is the [Pure Bash Bible](https://github.com/dylanaraps/pure-bash-bible). It contains lots of examples of Bash syntax and best practices.

When writing shell scripts, I highly recommend checking your scripts with the excellent [ShellCheck](https://github.com/koalaman/shellcheck) tool. It will help you to avoid common errors and pitfalls by displaying warnings and suggesting improvements.

## Summary

Well, it has been another lengthy post! But I think I covered the main points of working on the shell and writing shell scripts. It was also a good way to refresh my memory on signals and background jobs, as they are some features I do not use regularly.

As a summary, here are some takeaways:

* Use Bash for basic scripts
* For complex scripts, use a scripting language of your choice (Python, Perl, etc.)
* Return exit code 0 on success, non-zero on error
* Send input to a script via *standard input*, command line arguments and environment variables
* Write main output to *standard output* and log messages to *standard error*
* Create small scripts that compose well with each other and connect them with the pipe operator

Hope you find this useful. Keep on hacking!
