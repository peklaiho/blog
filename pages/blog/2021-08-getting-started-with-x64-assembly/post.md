---
title: "Getting Started with x64 Assembly"
date: "2021-08-08"
tags: [assembly, software-engineering]
---

A while ago I decided the learn the basics of the Assembly language for my x64 processor. Assembly has a reputation for being difficult and elitist which is keeping many people from trying it out. But once you learn the basics, it is actually really simple. In fact, in many ways it is much simpler than other programming languages. Using Linux as our host system, we can make system calls to the kernel for many operations like reading and writing standard input/output and files. Also the x86 instruction set allows us to perform string operations and "loops" directly with a single instruction, condensing many common operations into just a few lines of code.

I am writing this as a "beginner's guide" with the assumption that you have some knowledge of C (or similar language), but don't have knowledge of Assembly yet. That is also the place where I started my journey into the world of Assembly. I am not suggesting using Assembly as your daily language in projects where you need to be productive, but I think learning the basics is a valuable tool for understanding how a computer works at a lower level, and *will make you a better programmer in high-level languages*.

When writing Assembly, we are dealing with and manipulating the CPU directly. The CPU contains "memory locations" called **registers** and programming in Assembly consists mainly of moving data in and out of those registers and performing operations on the data while it is in a register, such as comparison or math operations like addition or subtraction. Additionally we have **jump** instructions that can jump to arbitrary place in the code (which is really just a memory address) and continue execution from that point. It works much like the **goto** statement in C. This conveniently handles all the cases where we would normally use **if**, **while**, **for**, or a function call.

## Assembler

To turn human-readable code into machine-readable code, we need an *assembler*. This is similar to *compiler* in C but simpler because the code we are writing is already closer to machine code to begin with. We will be using [NASM](https://nasm.us/), or Netwide Assembler, for these examples. It should be available as **nasm** package in all common Linux distributions. We will also be using the GNU linker, **ld**, which is in the **binutils** package, and the [GDB](https://www.gnu.org/software/gdb/) debugger.

Make sure those are installed before proceeding if you wish to have an interactive experience:

```
$ nasm --version
NASM version 2.15.05 compiled on Sep 24 2020
$ ld --version
GNU ld (GNU Binutils) 2.36.1
$ gdb --version
GNU gdb (GDB) 10.2
```

## Hello, World!

Before getting into the tedious technical details, lets start with a "Hello World" example that all software developers are familiar with. I guess it is something of a tradition that all writings about programming languages have to start with this specific example which outputs the text "Hello, World!" on the screen.

```x86asm
;; Hello World in Assembly

section .data
    text db `Hello, World!\n`

section .text

global _start
_start:
    mov rax, 1
    mov rdi, 1
    mov rsi, text
    mov rdx, 14
    syscall

    mov rax, 60
    mov rdi, 0
    syscall
```

Section `.data` is used to define initialized data like strings or more generally a sequence of bytes. The `db` stands for *define bytes*. Strings inside backquotes support escape sequences like the newline character shown here. Strings can also be delimited by normal single or double quotes.

Section `.text` is used to define code. Code is limited by **labels** which are just words followed by a colon. They mark a specific location in code that we can jump to. The `_start` is a special label which represents the starting point of the whole program, similar to the **main** function in C. The `global` keyword declares a label to be visible from outside the file. This would be similar to declaring it "public" in some other languages.

The code consists of performing two system calls. Each system call has a unique number and we store it in the RAX register. Number 1 corresponds to **write** and number 60 corresponds to **exit**. The second argument to write, stored in RDI, is the file where we want to write. Number 1 for files corresponds to standard output (**stdout**). The third argument is the text to write and 14 is the number of bytes to write.

If you save the file as *hello.asm* then you can assemble it with NASM into an object file:

```
$ nasm -f elf64 -o hello.o hello.asm
```

We use ELF64 as the format to specify we are assembling a 64-bit Linux application. Next we use the linker to turn the object file into a proper executable:

```
$ ld hello.o -o hello
```

If you have a larger program that consists of multiple source files, you can link multiple object files together into an executable just like you would in C. You can also mix object files from C and Assembly and link them all together into an executable.

Now we should have an executable ready to run, which does what we expect:

```
$ ./hello
Hello, World!
```

If you check the file sizes, they are really tiny:

```
$ ls -l
-rwxr-xr-x 1 pekka pekka 8840 2021-08-01 11:46 hello
-rw-r--r-- 1 pekka pekka  230 2021-08-01 11:28 hello.asm
-rw-r--r-- 1 pekka pekka  848 2021-08-01 11:42 hello.o
```

If you struggle with having "bloat" in your applications, this method of writing software is a good way to eliminate some of that. Next we must dwell a little deeper into some technical details to write meaningful programs that do something more interesting. But I will keep it simple, I promise!

## Registers

Registers are the heart and soul of your CPU. Here is a table of some important registers that you should know about. RAX to RBP and R8 to R15 are called general-purpose registers. You can freely use all general-purpose registers with some caveats explained below. Registers XMM0 to XMM15 are used for working with floating-point values.

| 128 bit       | 64 bit    | 32 bit      | 16 bit      | 8 bit MSB | 8 bit LSB   | Notes                                  |
|---------------|-----------|-------------|-------------|-----------|-------------|----------------------------------------|
|               | RAX       | EAX         | AX          | AH        | AL          | Accumulator                            |
|               | RBX       | EBX         | BX          | BH        | BL          | Base index (arrays)                    |
|               | RCX       | ECX         | CX          | CH        | CL          | Counter (loops, strings)               |
|               | RDX       | EDX         | DX          | DH        | DL          | Extend accumulator                     |
|               | RSI       | ESI         | SI          |           | SIL         | Source index (strings)                 |
|               | RDI       | EDI         | DI          |           | DIL         | Destination index (strings)            |
|               | RSP       | ESP         | SP          |           | SPL         | Stack pointer (top of stack)           |
|               | RBP       | EBP         | BP          |           | BPL         | Stack base pointer (bottom of stack)   |
|               | R8 to R15 | R8D to R15D | R8W to R15W |           | R8B to R15B | 8 additional general purpose registers |
|               | RIP       | EIP         | IP          |           |             | Instruction pointer (program counter)  |
|               | RFLAGS    | EFLAGS      | FLAGS       |           |             | Flags register                         |
| XMM0 to XMM15 |           |             |             |           |             | 16 floating-point registers            |

Since we are working with a 64-bit system and writing 64-bit code, we are mostly concerned with the column showing 64-bit registers starting with RAX. The 32-, 16- and 8-bit registers refer to the lower portions of the same 64-bit register. For example EAX refers to the lowest 32 bits of RAX, not a separate 32-bit register.

**FLAGS** is a special register which is used to test various conditions after comparison or arithmetic instruction is executed. For example, it can be used to check if the result of the last instruction is zero or not, if the result is even or odd, or if it caused an overflow. Here are some flags:

* **CF** - Carry flag
* **PF** - Parity flag
* **ZF** - Zero flag
* **SF** - Sign flag
* **OF** - Overflow flag
* **AF** - Adjust flag
* **IF** - Interrupt flag

Most of the time you don't need to deal with the FLAGS register explicitly and you can use conditional jumps instead like *jump-if-zero*, *jump-if-signed* or *jump-if-overflow* that implicitly check the corresponding flag.

## Instructions

There are lots of instructions in the x86-64 instruction set, but you only need a handful to get started with writing awesome code.

### Move

The `mov` instruction is used to move values into and out of registers. I would say it is the most used instruction. It takes destination and source as arguments:

```x86asm
;; Move value 5 into RAX register:
mov rax, 5

;; Move value of RBX into RAX:
mov rax, rbx
```

We can also have "pointers" by having a memory address in a register and then access that memory location directly. The notation for this is square brackets around the register name:

```x86asm
;; Move the value at memory address where RBX is pointing into RAX:
mov rax, [rbx]
```

### Unconditional Jump

Jump instructions allow us to jump to the location of a given label and continue execution from that point. The most simple case is the unconditional jump instruction `jmp` which always jumps without checking any conditions:

```x86asm
myLabel:
    jmp myLabel
```

The above code causes an infinite loop because the code jumps to `myLabel` indefinitely.

### Compare and Conditional Jump

Often it is more useful to jump only if a given condition is true, like the **if** statement in C. We can use the `cmp` instruction to compare two values. It takes the form `cmp a, b` where **a** is always a register and **b** is a register or a value. The compare instruction sets status flags in the FLAGS register according to the result.

After the compare instruction has been executed, we can perform a conditional jump based on the result of the comparison. Here are some instructions for conditional jumps:

| Instruction (signed) | Instruction (unsigned) | After `cmp a, b`, jump if |
|----------------------|------------------------|---------------------------|
| je                   |                        | a = b                     |
| jne                  |                        | a != b                    |
| jg                   | ja                     | a > b                     |
| jge                  | jae                    | a >= b                    |
| jl                   | jb                     | a < b                     |
| jle                  | jbe                    | a <= b                    |
| jz                   |                        | a = 0                     |
| jnz                  |                        | a != 0                    |
| jo                   |                        | Overflow flag is set      |
| jno                  |                        | Overflow flag is not set  |
| js                   |                        | Sign flag is set          |
| jns                  |                        | Sign flag is not signed   |

Note that some instructions have different versions for dealing with signed and unsigned values. Here is an example of compare and jump:

```x86asm
cmp rax, rbx
jg myLabel
```

This code would jump to `myLabel` if the value in RAX is greater than the value in RBX.

### Stack

The stack is a block of memory reserved for your application by the operating system. It operates as a Last-In-First-Out (LIFO) data structure. New items can be added on the top of the stack using the `push` instruction. Reading from the stack using the `pop` instruction always returns the last item that was added.

You can use the stack to store values temporarily. For example something like this:

```x86asm
push rax   ; push the current value of rax on stack
;; use rax for something else
pop rax    ; pop the last value from stack into rax
```

Note that it is generally better to use registers instead of stack when possible. Registers are located on the CPU itself while the stack is located in the RAM memory, so it is *significantly faster* to move values between two registers than access the stack.

The stack pointer (RSP) and stack base pointer (RBP) registers are used to manage the stack. Generally you don't need to use these registers directly.

### Arithmetic

Arithmetic functions take a register as the first argument and a register or a value as the second argument. The first register is the subject of the operation and the result is stored in it. Note that **reg** below refers to any register while **rax** refers to the specific RAX register. Generally these functions also set the status flags in the FLAGS register so you can immediately execute a conditional jump without using a `cmp` instruction in between.

| Instruction | Instruction (signed) | Notes           |
|-------------|----------------------|-----------------|
| add a, b    |                      | a = a + b       |
| sub a, b    |                      | a = a - b       |
| mul reg     | imul reg             | rax = rax * reg |
| div reg     | idiv reg             | rax = rax / reg |
| neg reg     |                      | reg = -reg      |
| inc reg     |                      | reg = reg + 1   |
| dec reg     |                      | reg = reg - 1   |
| adc a, b    |                      | a = a + b + CF  |
| sbb a, b    |                      | a = a - b - CF  |

Example:

```x86asm
mov rax, 3
mov rbx, 2
add rax, rbx
;; rax will contain the value 5
```

Division comes with a two caveats. First, RDX is used together with RAX as RDX:RAX to make a 128-bit value and then that value is divided. Second, the remainder of the division is stored in RDX. For example:

```x86asm
mov rax 7
mov rdx 0
mov rcx 3
div rcx
;; rax will contain value 2
;; rdx will contain value 1
```

### Instruction Reference

An excellent [reference for x86/x64 instructions](https://www.felixcloutier.com/x86/) is maintained by Félix Cloutier. Use it to look up any instructions and how to use them.

## Functions / Procedures / Subroutines

Just like in other languages, in order to write a larger program in Assembly, you need to divide it into functions that can be called independently. They are also called *procedures* or *subroutines* but I am not quite sure which is the most accurate term in this context. A function in Assembly is *just a label* followed by instructions (the function body) and finally a `ret` instruction.

A function is "called" with the `call` instruction which jumps to the given label and pushes a *return address* on the stack. The `ret` instruction then reads the return address from the stack and jumps to it.

### Calling Convention

*Calling conventions* were established in order to have a standard for how registers are used in function calls. Note that calling convention is specific to your operating system. This article deals with Linux specifically, but you can see the [Windows calling convention](https://docs.microsoft.com/en-us/cpp/build/x64-calling-convention) for comparison.

Function arguments are set in registers according to the following table:

| Argument # | Register in user-space | Register in kernel-space |
|------------|------------------------|--------------------------|
| 1          | RDI                    | RDI                      |
| 2          | RSI                    | RSI                      |
| 3          | RDX                    | RDX                      |
| 4          | RCX                    | R10                      |
| 5          | R8                     | R8                       |
| 6          | R9                     | R9                       |

All registers for arguments are same in user-space and kernel-space except the fourth. You should use the user-space convention in your own functions and the kernel-space convention when performing system calls.

Another part of calling convention determines who is responsible for restoring the original value into a register if it is modified. For this purpose registers are divided into *caller-saved* and *callee-saved* registers. The values of *caller-saved* registers must be saved by the parent procedure (caller), and possibly restored after the call into a subroutine has finished. The values of *callee-saved* registers must not be changed by the subroutine (callee), or if changed, must be restored to their original value before the call returns.

The registers RBX, RSP, RBP and R12 to R15 are *callee-saved*. All other registers are *caller-saved* and may be modified freely by the subroutine.

The return value of a procedure call is stored in RAX for integers and in XMM0 for floating-point values.

### System Calls

System calls are functions on the Linux kernel that we can call from our programs. They are identified by a number which is stored in the RAX register and the system call is invoked with the `syscall` instruction.

Here are a few system calls for working with files and exiting the program:

| Name  | Id | Arg 1           | Arg 2  | Arg 3  |
|-------|----|-----------------|--------|--------|
| read  | 0  | file descriptor | buffer | length |
| write | 1  | file descriptor | buffer | length |
| open  | 2  | filename        | flags  | mode   |
| close | 3  | file descriptor |        |        |
| exit  | 60 | exit code       |        |        |

You can find a list of all supported system calls and their numbers in the file **/usr/include/asm/unistd_64.h** on your system.

## Standard Input/Output

On Linux it is convenient to use standard input and output to print text on the screen or read user input. Here are the file descriptor numbers for them:

* **0**: standard input (**stdin**)
* **1**: standard output (**stdout**)
* **2**: standard error (**stderr**)

## Hello, Again!

Well, that was a lot of technical stuff! But if you are still reading, you can perform a deep sigh of relief, because now we are ready to write some real programs instead of just learning about registers and conventions.

Lets modify our original "Hello World" program a bit. This time we will read user input and split our code into separate functions:

```x86asm
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
```

We have a new section here called `.bss`. It is used to define uninitialized data. The `resb` stands for *reserve bytes* which simply reserves a block of memory that can be used for reading and writing. We reserve a buffer of 16 bytes to hold user input.

Save the file as *hello2.asm*, assemble and run it:

```
$ nasm -f elf64 -o hello2.o hello2.asm
$ ld hello2.o -o hello2
$ ./hello2
What is your name? Hacker
Hello, Hacker
```

Now we are cooking! And it's not *that many* lines of code considering we can already handle input and output by leveraging the kernel.

However, there are still a few things we should improve.

## Constants

Using plain numbers in code is not very convenient and we can make the code more readable by using constants. Constants can be defined in NASM using the `equ` expression. For example:

```x86asm
STDERR equ 2
SYS_EXIT equ 60
```

## Macros

NASM supports macros which allow us to replace code similar to macros in the C preprocessor. This allows us to give inline arguments to function calls and wrap the `mov` instructions inside the macro.

Here is an example of a macro which allows us to call a function that takes 2 arguments:

```x86asm
%macro call2 3
    mov rdi, %2
    mov rsi, %3
    call %1
%endmacro
```

The arguments to the macro are referenced by `%1`, `%2` and so on. The number 3 after the name means it takes 3 arguments.

Lets create a separate file called *macro.asm* where we can store convenient macros and include them in other files. This is similar to a header-file in C:

```x86asm
%macro call1 2
    mov rdi, %2
    call %1
%endmacro

%macro call2 3
    mov rdi, %2
    mov rsi, %3
    call %1
%endmacro

%macro call3 4
    mov rdi, %2
    mov rsi, %3
    mov rdx, %4
    call %1
%endmacro
```

## Library Functions

Finally, we should split our application into generic library-code that can be used anywhere and into application-code that is specific to our "Hello World" application.

Another inconvenient part of our application is that we have to manually give the length of the string when printing it. It would be better to have a print function that just takes a null-terminated string and calculates the length automatically.

With these improvements in mind, lets first create a separate file called *lib.asm* and define a few generic functions:

```x86asm
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
```

Now we introduce the `strlen` function which contains a few new instructions. The `xor` instruction at the beginning is used to set the register to zero. The really interesting part is the `repne scasb` line. The `scasb` compares the input string one byte at a time to the value in RAX and the `repne` repeats it as long as the result is not equal. So we continue until a zero is found, thus giving us the length of a null-terminated string.

This means that we don't need to use a jump instruction to create a loop. Instead we can use special instructions provided by the CPU to run the loop directly on the CPU. I think that is really cool!

## Hello, Final!

Now we have the final version of our Hello World application:

```x86asm
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
```

We include our header file with the `%include` directive. We also need to use `extern` to introduce functions which are defined in another file. Strings need to be null-terminated for our `strlen` function to work correctly.

Now we need to assemble both files and link them together into an executable:

```
$ nasm -f elf64 -o lib.o lib.asm
$ nasm -f elf64 -o hello3.o hello3.asm
$ ld lib.o hello3.o -o hello3
$ ./hello3
What is your name? Superman
Hello, Superman
```

Our app still works and the code is starting to look pretty neat!

## Debugging with GDB

In higher-level languages it is often easy to perform basic debugging by printing some debug information on the screen or a log file. While possible in Assembly, it's simply not practical. Therefore one of the most important tools to go with Assembly development is the [GDB](https://www.gnu.org/software/gdb/) debugger. With the debugger you can execute one instruction at a time and view the values of the registers to see exactly what is happening. Keep it always with you like a trusted friend when working in Assembly.

How to use GDB would require a completely separate article but here are some basics. Use the `-g` flag to include debug information in the assembled files:

```
$ nasm -f elf64 -g -o lib.o lib.asm
$ nasm -f elf64 -g -o hello3.o hello3.asm
$ ld lib.o hello3.o -o hello3
```

Next start gdb and give the executable as argument:

```
$ gdb hello3
GNU gdb (GDB) 10.2
Copyright (C) 2021 Free Software Foundation, Inc.
Reading symbols from hello3...
(gdb)
```

You will get the debugger prompt shown as **(gdb)** where you can type commands. Normally we want to set a breakpoint at some label and when the code reaches it we want to pause execution and inspect the internal state of the program.

Set a breakpoint using `break` and the name of a label. For example:

```
(gdb) break strlen
Breakpoint 1 at 0x401033: file lib.asm, line 48.
```

Now start the program using `run`:

```
(gdb) run
Starting program: hello3

Breakpoint 1, strlen () at lib.asm:48
48	    xor rax, rax
(gdb)
```

The execution pauses at the breakpoint and GDB displays the next instruction to be executed as `xor rax, rax` which is the first instruction of the *strlen* function.

You can use the command `info r` to display the values of all registers:

```
(gdb) info r
rax            0x0                 0
rbx            0x0                 0
rcx            0x0                 0
rdx            0x0                 0
rsi            0x402000            4202496
rdi            0x402000            4202496
rbp            0x0                 0x0
rsp            0x7fffffffe6a0      0x7fffffffe6a0
r8             0x0                 0
r9             0x0                 0
r10            0x0                 0
r11            0x0                 0
r12            0x0                 0
r13            0x0                 0
r14            0x0                 0
r15            0x0                 0
rip            0x401033            0x401033 <strlen>
eflags         0x202               [ IF ]
cs             0x33                51
ss             0x2b                43
ds             0x0                 0
es             0x0                 0
fs             0x0                 0
gs             0x0                 0
```

Use the `step` command to execute one instruction at a time. For example, if you step twice and then check the registers again, you will see that the value of RCX was indeed set to -1:

```
(gdb) step
49	    mov rcx, -1
(gdb) step
50	    cld
(gdb) info r rcx
rcx            0xffffffffffffffff  -1
```

Step a few more times to execute the `repne scasb` instruction:

```
(gdb) step
51	    repne scasb                 ; loop until [rdi] != rax
(gdb) step
52	    mov rax, rcx
(gdb) info r rcx
rcx            0xffffffffffffffeb  -21
```

Now we see the value in the RCX register is -21. This is really awesome!

Use `continue` to continue execution at full speed until the next breakpoint:

```
(gdb) continue
Continuing.
What is your name? Bob

Breakpoint 1, strlen () at lib.asm:48
48	    xor rax, rax
(gdb)
```

Execution is paused at the next breakpoint which occurs at *strlen* again.

Use `quit` to exit the debugger.

## More Examples

I will add a few more examples of some simple functions I wrote. Please take these with a caution that they have not been extensively tested and may contain bugs. But they might be useful to get some ideas of what you can do with Assembly.

```x86asm
;; Compare two memory locations
;; Inputs: RDI, RSI, RDX = length
memcmp:
    mov rcx, rdx
    cld
    repe cmpsb                  ; repeat until equal or rcx=0
    jz .equal
    jns .negres
    mov rax, 1
    ret
.negres:
    mov rax, -1
    ret
.equal:
    mov rax, 0
    ret

;; Copy bytes from source to destination
;; Inputs: RDI = destination, RSI = source, RDX = length
memcpy:
    mov rcx, rdx
    cld
    rep movsb                   ; copy RCX bytes from RSI to RDI
    ret

;; Set bytes to specified value
;; Inputs: RDI = destination, RSI = value to set, RDX = length
memset:
    mov rax, rsi
    mov rcx, rdx
    cld
    rep stosb
    ret

;; Compare two strings
;; Inputs: RDI, RSI
strcmp:
    mov al, [rdi]
    cmp al, [rsi]
    jne .noteq
    test al, al
    jz .equal
    inc rdi
    inc rsi
    jmp strcmp
.equal:
    mov rax, 0
    ret
.noteq:
    js .negres                  ; check sign flag (SF)
    mov rax, 1
    ret
.negres:
    mov rax, -1
    ret

;; Copy null-terminated string
;; Inputs: RDI = destination, RSI = source
strcpy:
    cld
.loop:
    lodsb
    stosb
    test al, al
    jnz .loop
    ret
```

One useful thing to know is that if a label name starts with a dot, it is treated like a private sub-label under the main label. This means that you can have sub-labels with the same name under multiple parent-labels and it does not cause a namespace conflict.

## Conclusions

Well, this has been quite a long post and it's time to wrap it up. It's difficult to be concise but at the same time include all the relevant information.

As for the benefits of learning Assembly, I think it's really useful to understand how a CPU works. Higher-level languages hide all of this away and most developers writing software today have no idea what is happening under the surface. In addition to increasing your knowledge, consider the obvious benefits for your social life. Just imagine standing in a room full of JavaScript developers and casually dropping that you write Assembly. That is some serious "geek cred" right there.

Hope you have fun exploring some things shown here.
