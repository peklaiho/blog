;; Improved Hello World

section .data
    text1 db "What is your name? "
    text2 db "Hello, "

section .bss
    input resb 16

section .text

global _start
_start:
    mov rdi, text1
    mov rsi, 19
    call print                  ; print text1
    call read                   ; read input
    mov rdi, text2
    mov rsi, 7
    call print                  ; print text2
    mov rdi, input
    mov rsi, 16
    call print                  ; print input
    call finish

finish:
    mov rax, 60                 ; exit
    mov rdi, 0                  ; exit code
    syscall

read:
    mov rax, 0                  ; read
    mov rdi, 0                  ; stdin
    mov rsi, input              ; buffer
    mov rdx, 16                 ; length
    syscall
    ret

print:
    mov rax, 1                  ; write
    mov rdx, rsi                ; length
    mov rsi, rdi                ; buffer
    mov rdi, 1                  ; stdin
    syscall
    ret
