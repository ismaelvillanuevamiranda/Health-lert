#!/bin/bash

var=$(node gt.js fever)

echo "$var" > temp.txt

# val=$(printf "%s" $(node gt.js fever))
# echo "-------------[ $val ]--------------";

# exec 3< <(node gt.js fever);
# lines=();
# while read -r; do lines+=("$REPLY"); done <&3;
# exec 3<&-;
# echo "${lines[@]}";

