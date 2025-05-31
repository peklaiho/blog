;; Final version of Hello World

;; Include a header file
%include "macro.asm"

;; Functions defined in library file
extern exit, gets, prints

section .data
    ;; Terminate strings with null
    text1 db `What is your name? \0`
    text2 db `Hello, \0`

section .bss
    input resb 16

section .text

global _start
_start:
    call1 prints, text1
    call2 gets, input, 16
    call1 prints, text2
    call1 prints, input
    call exit
