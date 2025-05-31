<?php
echo "Count: $argc\n";

echo "Values:\n";

for ($i = 0; $i < $argc; $i++) {
    echo "$i: $argv[$i]\n";
}
