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
