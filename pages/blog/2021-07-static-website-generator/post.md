---
title: "Static Website Generator"
date: "2021-07-26"
tags: [php, software-engineering, website]
---

I have not been updating my website and blog in a while. Part of the reason is that I was not fully satisfied with the software powering the site. The site was running on [Grav CMS](https://getgrav.org/), and don't get me wrong, it is a great piece of software for hosting a website. With Grav all the content of the website is stored in Markdown files and having a database is not required. I much prefer Markdown files since they are a joy to edit and can be stored in a Git repository to keep version history. It suited my preferences much more than something like [Wordpress](https://wordpress.org/) and it was more lightweight also.

The issue with Grav was that it is another piece of software that needs to be managed and needs updates and maintenance. And from the perspective of trying to keep web-facing servers secure, any publicly exposed service running dynamic code is a potential security vulnerability. Given that my site is so simple, there is really no need for any dynamic code to run whenever someone visits the site. It is perfectly adequate to serve static HTML files. This way I can cut out one layer of software that I would need to keep up to date.

So I figured it was time to look into static website generators. Given that my content was already in Markdown, moving it over would require only minimal changes. There are lots of static website generators out there, like [Hugo](https://gohugo.io/) for example, that probably would have served me well. But the problem I faced is that if I go with something like Hugo, then I have to spend lots of time reading their documentation and learning it. Because each tool has their own conventions and opinions about how a site should be structured and configured. But I just need something simple that converts Markdown into HTML. I don't want to spend a lot of time reading documentation about *how to use the tool*, rather I want a simple tool that *just works*.

I ended up with writing my own static site generator. The benefit of a custom tool is that you have fine-grained control over even the tiny details, and the result fits perfectly for what you need. And once you get somewhat proficient in writing software, it's not actually much more work (as in time spent) than learning to use an existing tool that is somewhat complex.

## Stagger

My site generator is called [Stagger](https://bitbucket.org/maddy83/stagger/) and it is released under the MIT license.

Luckily the PHP ecosystem has so many stable and high quality libraries available for common tasks that we developers are spoiled for choice. I used [CommonMark](https://commonmark.thephpleague.com/) from *The PHP League* for rendering Markdown and [Twig](https://twig.symfony.com/) for HTML templates. For CSS I am using [Spectre.css](https://picturepan2.github.io/spectre/). Before I have mostly used [Bootstrap](https://getbootstrap.com/) but Spectre is really great. It is minimalistic and gives the site a clean and stylish look out of the box.

One feature I prefer to have on my blog is syntax highlighting for code snippets since I post them fairly regularly. There are some popular libraries available for syntax highlighting in JavaScript, but I was particularly happy to find the [highlight.php](https://github.com/scrivo/highlight.php) package. Using it I can do all of the syntax highlighting server-side while generating the site and I don't need dependency on JavaScript.

If you are looking for a minimalistic static site generator that you can just start using without reading through many pages of documentation, give Stagger a try. You can install it using [Composer](https://getcomposer.org/) like this:

```
$ composer create-project maddy83/stagger mysite
Creating a "maddy83/stagger" project at "./mysite"
```

Please report any bugs or other issues that you find. As for new features, the plan is not to add too many, since it should be kept fairly simple. After all, there are many other site generators with tons of features if that is what one is looking for.

As for myself, I hope I will find the time to update the blog a little more frequently again in the future.
