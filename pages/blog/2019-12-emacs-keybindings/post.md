---
title: "Customizing Emacs Keybindings"
date: "2019-12-14"
tags: [emacs]
---

I have been using [Emacs](https://www.gnu.org/software/emacs/) as my text editor for a few years now. One of the best features about emacs is that you can customize everything. This is because of the underlying lisp interpreter that allows you to define custom functions and then bind them to keys. This is in contrast to a "normal" text editor which allows you to change keybindings, but only to pre-defined built-in functions which perform the text manipulation.

One of my keybindings is to move to next paragraph by <kbd>M-n</kbd> and back to previous paragraph by <kbd>M-p</kbd>. This makes sense to me because we have the default keys <kbd>C-n</kbd> and <kbd>C-p</kbd> to move to next and previous line. Keys are bound using the `global-set-key` function.

```lisp
(global-set-key (kbd "M-n") 'forward-paragraph)
(global-set-key (kbd "M-p") 'backward-paragraph)
```

This works most of the time, but some major modes override this with their own keybindings. A good example of this is the Markdown mode, which I am using to write this blog post. So while editing Markdown files, pressing <kbd>M-n</kbd> or <kbd>M-p</kbd> does not give us the expected result.

Emacs has useful trick to find out which function a given keybinding executes. We can get this by <kbd>C-h k</kbd> which gives us a prompt. Now we just execute the given keybinding that we want to know about. Running this in Markdown mode tells us that <kbd>M-n</kbd> and <kbd>M-p</kbd> are bound to `markdown-next-link` and `markdown-previous-link` respectively. This is obviously not good, because I want to use same commands consistently in all files that I edit.

Fortunately this can be fixed. Major modes offer something called a _hook_ which is a custom function you can define that is executed right after the associated major mode has been initialized. Being a function, hooks can be used to execute arbitrary code, and set all kinds of settings, not just keybindings.

```lisp
(defun my-markdown-mode-hook ()
  "Initialize settings for markdown mode."
  (define-key markdown-mode-map (kbd "M-n") 'forward-paragraph)
  (define-key markdown-mode-map (kbd "M-p") 'backward-paragraph))
```

Notice that now we edit the `markdown-mode-map` instead of the global keymap. Next we just have to define our function as the hook function for Markdown mode and make sure it is executed during Emacs startup.

```lisp
(add-hook 'markdown-mode-hook 'my-markdown-mode-hook)
```

## Dired mode

One of the useful features of Emacs is the [Dired](https://www.gnu.org/software/emacs/manual/html_node/emacs/Dired.html) mode which is used to view and manipulate directories.

However, once I started to use Dired, I noticed I am not happy with the default keybindings. By default <kbd>RET</kbd> is bound to `dired-find-file` which opens the selected file or directory in a new buffer, but leaves the old buffer open as well. After browsing around in directories for a while you can have a dozen Dired buffers open. This is not desirable for me as it adds extra clutter when I am trying to find some open buffer using <kbd>C-x b</kbd>.

There is another way to open files in Dired, `dired-find-alternate-file` which is bound to <kbd>a</kbd>. This opens the selected file but also kills the current buffer. I was surprised to find out that this command was disabled by default, and first time Emacs gave me a prompt to ask if I want to use it. To have it enabled by default, add the following to your init file.

```lisp
(put 'dired-find-alternate-file 'disabled nil)
```

Since the return key is more convenient to use than the a-key, a simple solution would be just to swap these default keybindings, and that would solve the clutter of Dired buffers. However, I realized this is not exactly what I want either. By default I want to keep the current Dired buffer when opening _files_ but not when opening other _directories_. This way I keep exactly _one_ Dired buffer open, but no more.

To get the desired functionality, I defined a couple of custom functions. First `my-dired-find-file` performs what I want, and then `my-dired-find-alternate-file` has the opposite behavior. We can test if something is a file or directory by using `file-directory-p`.

```lisp
(defun my-dired-find-file ()
  "Open files in new buffer but not directories."
  (interactive)
  (let ((f (dired-get-file-for-visit)))
    (if (file-directory-p f)
        (dired-find-alternate-file)
      (dired-find-file))))

(defun my-dired-find-alternate-file ()
  "Open directories in new buffer but not files."
  (interactive)
  (let ((f (dired-get-file-for-visit)))
    (if (file-directory-p f)
        (dired-find-file)
      (dired-find-alternate-file))))
```

Then we just have to define a hook function for Dired mode similar to above and everything works as expected.

```lisp
(defun my-dired-keys ()
  "Switch RET and a keys for dired."
  (define-key dired-mode-map (kbd "RET") 'my-dired-find-file)
  (define-key dired-mode-map (kbd "a") 'my-dired-find-alternate-file))

(add-hook 'dired-mode-hook 'my-dired-keys)
```

In case you are wondering, I tend to prefix my functions with `my-` to avoid collisions with other functions.

Hope this post was of some use to demonstrate capabilities of Emacs. The more I use the editor, the more I like it, and hopefully I will be able to share more of my learning journey in the future on this blog.
