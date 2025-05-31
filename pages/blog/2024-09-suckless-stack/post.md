---
title: "The Suckless Stack"
date: "2024-09-29"
tags: [linux, software-engineering]
---

In this post I am going to talk about the [Suckless](https://suckless.org/) software tools and how I have integrated them into my workflow and daily use. Suckless tools are interesting because they are a little different from the mainstream in terms of how their software is built, configured, packaged and delivered. Their websites summarizes their mission statement as *quality software with a focus on simplicity, clarity, and frugality*. Lets dig a little deeper into what makes Suckless software unique.

Suckless software is targeted towards advanced computer users. They do not try to hide this or claim that their products are beginner-friendly. Targeting a specific demographic is a good thing, because then you can deliver a product that really serves the needs of that group, instead of trying to cater to everyone. Too many products try to cater to everyone and by doing so leave all groups equally unhappy.

The first unique aspect of Suckless software is that the tools are configured by editing the source code directly and then recompiling the program with the new settings. They do not have configuration files by default like almost all other software does. This is pretty wild when you encounter it for the first time! But actually compiling your own software used to be the norm in the Unix/Linux world before package managers were in widespread use like today. You can still install most of the Suckless tools using a package manager, but that would defeat the whole purpose of configuring them and making them your own.

Another aspect of Suckless is that the tools are generally very stable and changes are rare. The tools are minimal by nature and have strictly defined functionality that they need to accomplish. After a tool satisfies the core requirements, no new features are added and it is considered essentially complete. Of course bugs and security issues will be fixed if any are discovered. This is in stark contrast to most other software which receive new features endlessly and slowly but surely become bloated.

Compiling a program from source code can be intimidating to many users who are not used to it. Most Linux users install precompiled software by running `sudo apt install` or `pacman -S` and everything just works. Easy and convenient, no hassle. By the way, package managers are great and that is how I want to handle 95% of my software installations also. But be open to tinkering with that remaining 5%. Do not be scared to get your feet wet.

The main reason why people develop such a disgust for compiling software themselves is that in the majority of cases trying to compile something never goes smoothly. There is always some obscure error message (or at least warnings) that you have no idea how to solve. Or there are dependencies to third party libraries that you have to install, and it may not be obvious which library or how to install it. But the reason for these problems is not that compiling itself is problematic or something to be feared. It is poor quality software and lack of good documentation.

Suckless software on the other hand is a perfect starting place for compiling your own tools for these reasons:

* The tools are tiny and compile instantly, so no waiting
* They are self-contained and have minimal external dependencies
* Generally they compile flawlessly without any errors or warnings

Note: Most of the Suckless tools work with the X window system so they may not work if you are running Wayland.

## Common Steps

Before going into the specific tools, here are the common steps how to compile and use a Suckless tool.

First you need to have the common build tools (a C compiler and Make) installed on your Linux system. This is package `base-devel` on Arch and `build-essential` on Debian/Ubuntu.

1. Do a `git clone` of the tool you want to try
1. Copy the file `config.def.h` as `config.h`
1. Edit `config.h` file and make your own customizations
1. Run `make` to compile the software
1. Run `make install` (with *sudo*) to install it into your system

If you get a warning that some header file is missing when compiling, then you need to install the corresponding package first (using your normal package manager with `apt install` or `pacman -S`). Packages that contain header files are normally libraries (packages start with the `lib` prefix) or development packages that have the `-dev` suffix. Finding the right package requires some deduction and sometimes a little bit of trial and error.

Just as an example, the tool `st` uses a header file `X11/Xft/Xft.h`. On Arch that file is found in the package `libxft` and on Debian/Ubuntu the correct package is `libxft-dev`.

Running `make install` puts the executable in the `/usr/local/bin` directory by default. This directory is separate from where your package manager puts executables. But sometimes you do not want to install everything system-wide. Instead you can just put the executable in `~/bin` and add that directory to your `$PATH`. Then it is usable for your current user and not installed system-wide.

## Patches

As stated above, the tools are very minimal in their default configuration. Most users will want more features than what is available out-of-the-box. But the key point is that different users will want different features. Adding all the extra features to the core version of the tool would cause it to become bloated and force all users to live with lots of extra features that they never need. Instead, Suckless handles additional features with *patches*. Every user can choose which patches they want and combine them into a final version, unique to them and their needs.

Patches are distributed as `diff` files and can be downloaded from the Suckless website. A patch can be applied automatically by running the patch command `patch < patchfile.diff` in the source directory of the tool. Most of the time this just works and everything goes well. However if you are installing multiple patches on top of each other, they may require manual intervention if the automatic patch program cannot figure out how to apply the changes. In that case you need to open an editor and fix the problematic parts manually.

## dwm

[dwm](https://dwm.suckless.org/) is a tiling window manager and probably the most well known of Suckless software. Tiling window managers use all of the available screen area by automatically adjusting sizes of application windows. If you open two windows they appear next to each other. If you close one, the remaining one will take up all the screen area again. This is very efficient because you don't need to resize windows and you are not wasting any screen area that is unused.

Overall *dwm* is a simple application that just does its job and gets out of your way. That is exactly what you want from your window manager. I think the only official patch I used was [attachbottom](https://dwm.suckless.org/patches/attachbottom/). I also disabled "focus follows mouse" functionality which I found annoying.

The text for the status bar displayed in the top right corner is set with the command `xsetroot -name "my-status-text"`. I wrote a [custom script](https://github.com/peklaiho/mad-status-bar) for generating the status bar text.

## st

[st](https://st.suckless.org/) (simple terminal) is the terminal emulator of Suckless. True to Suckless form, it is very minimal and does not even support scrolling back the buffer by default. That can be easily fixed with the [scrollback](https://st.suckless.org/patches/scrollback/) patch. Another patch I applied was [anysize](https://st.suckless.org/patches/anysize/). This makes it so that the terminal window uses all of the available space and adds padding inside its own borders, instead of matching the window size to a specific number of rows and columns based on the font. It looks much better that way!

## dmenu

[dmenu](https://tools.suckless.org/dmenu/) is the Suckless application launcher. It appears as a bar at the top of the screen. Then you start writing the name of the application that you wish to run and it shows all the matching suggestions. It is very simple, just does its job and gets out of your way. Just what I want!

## slock

[slock](https://tools.suckless.org/slock/) is the Suckless display locker. For those times when you need to lock your computer without closing it. Handy for those bathroom breaks. It is basically just an empty screen that can change its color and you unlock it by entering your password. But there is no text and no prompt that tells you to type your password. So if someone else tries to access your computer, and they are not familiar with *slock*, it can look very confusing indeed!

## Conclusion

I have used Suckless as my daily driver for about 2.5 years now. I couldn't be happier with it. It does what I need and nothing more. I also love the fact that the tools are so mature and stable. I don't need to worry about some update breaking my window manager or terminal. Updates breaking your workflow happen *all the time* in other software that is continuously updated. And yes, there are times when I want to use the *bleeding edge* version of an application and live with the breakages. But a window manager is not one of them. I think you only learn to appreciate this level of stability once you experience it. When a piece of software just works flawlessly, day after day and year after year.

There are other tools in Suckless also but I have not used them yet. Another prominent one is [surf](https://surf.suckless.org/), the Suckless web browser. Perhaps I will give it a try at some point!
