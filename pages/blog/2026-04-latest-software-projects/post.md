---
title: "Latest Software Projects"
date: "2026-04-26"
tags: [software-engineering]
---

This post is a short summary of my latest software projects that I have worked on so far this year. It has been a while since my last blog post so I need any excuse I can find to write something down. The common reason for writing new software is a personal need that is not quite satisfied with existing free offerings. The added benefit of writing your own software is that you understand it completely so making modifications and adding features in the future becomes extremely easy. Furthermore, writing your own software is easier than ever with modern AI assistants, so I think everyone should do it!

## Deven

[Deven](https://github.com/peklaiho/deven) is probably my most interesting project so far this year. It is a tool for managing virtual machines for development environments. I treat it as a replacement for [Vagrant](https://developer.hashicorp.com/vagrant). But why did I want to replace Vagrant? That questions requires some introspection.

My first and most obvious pain point with Vagrant became the fact that it was no longer offered on my OS (Arch Linux) natively. It is only offered as a [package](https://aur.archlinux.org/packages/vagrant/) through the third-party *AUR* repository. I don't really like using AUR packages because they are not officially supported and have to be built manually. More importantly, there could be all kinds of compatibility issues with other packages that may need to be solved manually.

The second thing I don't like about Vagrant is how it uses its own format for VM images (called *Vagrant boxes*). The official repository of Vagrant boxes contains mostly very old and obsolete operating systems. For example, the most used versions of Ubuntu are 14.04 and 16.04. Those are 12 and 10 years old respectively! Ubuntu also stopped providing official Vagrant images starting from 24.04. That means I would have to rely on third-party images (which have serious security implications) or spend time learning how to create my own. Similar to Arch, Ubuntu also does not provide Vagrant as an official package anymore. What I need for development environments is an easy way to grab a *modern* OS image (such as latest Debian or Ubuntu), create a VM with one command and get going.

Vagrant looks like a dying product that is no longer supported by default on major operating systems. When I thought about this problem, I also realized there is no need for Vagrant at all. The key piece of software for creating development VMs is *VirtualBox*. You can just as well create a VM manually and set up port forwarding and shared folders. Vagrant is just a convenience layer that is supposed to make it easier. But convenience and especially repeatibility are important, so those were the reasons behind my decision to write Deven.

In the process I learned that there is a single command-line application `VBoxManage` that does everything that is needed for creating and managing VirtualBox VMs. Basically Deven is just a clean wrapper around VBoxManage. It also downloads the OS image but defaults to the *official* and *latest* image of Debian, instead of using custom image format like Vagrant.

I must note that while I am a little critical of Vagrant here, I generally consider Hashicorp products to be awesome! I just hope that the quality of their software holds up now that IBM acquired them. The prevailing opinion in the industry seems to be that IBM does not have a good track record of being a good steward for projects that they have acquired in the past.

## Package Audit

[pkg-audit](https://github.com/peklaiho/pkg-audit) is the second project I worked on. It is a very simple tool that compares a list of installed packages on a Linux system against a list of desired packages. The output then shows if any requested packages are missing, or if any extra packages are installed that were not requested. The key mechanic is the recursive handling of dependencies, so it does not count any dependencies of required packages as unnecessarily installed.

Some background for the motivation behind this project is in order. I was seriously considering switching from Arch Linux, my trusted workhorse of many years, into [NixOS](https://nixos.org/). I was lured by the idea of having a declaratively configured system where I just list the packages I want in a config file and then I know exactly what is installed. This declarative way of managing a system using NixOS is preached with religious fervor in many corners of the internet, so it is only natural I was interested in trying in. After all, it sounded like I was missing out on something great.

When learning about NixOS, my first disappointment came when I realized that NixOS actually includes of a lot of extra packages by default, before I have even begun listing which packages I want to install in my system. For me, that kind of defeats the purpose of it. I was shocked to realize that Arch is actually much more minimal out-of-the-box than NixOS in terms of installed packages.

My thinking was that instead of switching to NixOS, I can just stay in Arch and create a simple tool to control what packages I have installed and audit that against a version-controlled list of packages that I want. A simple way to guard against bloat if I decide to install something and then forget to remove it when it is no longer needed. Thus *pkg-audit* was born.

Another benefit of NixOS that is often cited is the ability to run one-off commands without installing a package permanently. But I have already largely solved this issue by using [Docker](https://www.docker.com/) for running one-off commands using packages that I do not want to permanently install. As for development environments, another common reason for using NixOS, I obviously have *Deven* now!

My conclusion is that NixOS does not offer any substantial benefit over Arch for me at this time. It is good to think for yourself instead of blindly following the "declarative configuration" hype crowd! I may change my opinion in the future, but for now I will continue to be a happy and loyal user of Arch.

## Golf Tracker

I am trying to get back into golf again after a having break of many years. Thus I wanted a simple way to keep track of my scores and basic stats. The ability to track my progress and see my improvement over time is definitely an extra motivational factor. Thus [Golf Tracker](https://github.com/peklaiho/golf-tracker) was born. It is a simple web app made with [CakePHP](https://cakephp.org/). I chose Cake instead of Laravel because I wanted a PHP-only solution. Laravel has transformed into a framework that is connected to the Node/NPM ecosystem now which I specifically wanted to avoid for this project. Similarly, I was thinking of using [Tailwind](https://tailwindcss.com/) as the CSS framework but rejected it for the same reason. Tailwind also seems to require a build step with a hard dependency on Node. I settled on [Bulma](https://bulma.io/) for the simplicity and no builds. I just want to include one CSS file and be done with it!

Initially this is just a very simple CRUD app. Just a place to enter my rounds with minimal effort. I can always make it pretty later! The important aspect that I wanted to get right immediately is the database schema. I want to avoid large refactors of the database schema later because then there is migration of the existing data and other extra work. Good database structure makes it easy to process the data later and create all kinds of statistics that I may want in the future.

Now I am facing the gargantuan task of entering all my old rounds into the database. But after that is done I will have a nice database of where I have played and all my scores. Good thing that I have saved my old score cards instead of throwing them away!

## Speaking Computer

The proliferation of LLM models that can handle speech processing has opened some interesting opportunities. There are quite nice models now for both *speech-to-text* and *text-to-speech* available. Importantly these models are lightweight enough to run on your local computer for free. I create a little project called [Speaking Computer](https://github.com/peklaiho/speaking-computer) to try this out. It is just a simple Docker container that uses [Pocket TTS](https://github.com/kyutai-labs/pocket-tts) to speak text that is given as argument. Initially useful for having spoken notifications for events such as finishing a download or other long-running task. I think working with speech is definitely a topic I should explore more in the future, given how easy and convenient these new local LLM models are.

## Conclusions

That concludes my short write-up about my latest projects. The common thread here is having a need and then writing a custom software to fill it. Creating custom software is easier than ever before in our AI-assisted era. And having AI available does not exclude a programmer from writing code by hand. There are situations where I profoundly enjoy writing code by hand and then letting AI review it if needed. Then there are other situations where I profoundly enjoy giving instructions to an LLM and constrain myself to a code reviewing role. You have the ability of having the best of both worlds, instead of locking yourself into a box of only doing things one way! While AI may largely take over commercial software development, at least we will always have our hobby projects where financial interests are secondary to the *joy of programming*!
