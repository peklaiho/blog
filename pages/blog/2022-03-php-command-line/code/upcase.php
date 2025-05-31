<?php
while (!feof(STDIN)) {
    $input = fread(STDIN, 128);
    if ($input !== false) {
        $output = strtoupper($input);
        fwrite(STDOUT, $output);
    }
}
