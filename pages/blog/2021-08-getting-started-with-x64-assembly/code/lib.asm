;; File descriptors
STDIN equ 0
STDOUT equ 1
STDERR equ 2

;; Syscalls
SYS_READ equ 0
SYS_WRITE equ 1
SYS_EXIT equ 60

;; Export functions
global exit, gets, prints, strlen

section .text

;; Exit the program
;; Inputs: RDI = exit code
exit:
    mov rax, SYS_EXIT
    syscall                     ; rdi is passed on unchanged
    ret

;; Read input from stdin
;; Note that result includes newline character
;; Inputs: RDI = buffer, RSI = length
gets:
    mov rax, SYS_READ
    mov rdx, rsi                ; arg3: length
    mov rsi, rdi                ; arg2: buffer
    mov rdi, STDIN              ; arg1: file
    syscall
    ret

;; Print null-terminated string to stdout
;; Inputs: RDI
prints:
    mov rsi, rdi                ; arg2: string (strlen does not modify rsi)
    call strlen                 ; length into rax
    mov rdi, STDOUT             ; arg1: stdout
    mov rdx, rax                ; arg3: length
    mov rax, SYS_WRITE          ; syscall id
    syscall
    ret

;; Calculate length of null-terminated string
;; Inputs: RDI
strlen:
    xor rax, rax
    mov rcx, -1
    cld
    repne scasb                 ; loop until [rdi] != rax
    mov rax, rcx
    add rax, 2
    neg rax
    ret
