#!/bin/bash

symptoms=`awk -v disease="$1" -f getSymptoms.awk predictions/ncomms5212-s4.txt`

similar=`awk -v disease="$1" -f getSimilarDisease.awk predictions/ncomms5212-s5.txt`

echo "$symptoms@$similar"