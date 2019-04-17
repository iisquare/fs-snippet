#!/bin/bash
field="title"
for ((i=1; i<=30;i++))
do
{
    for ((j=1; j<=100;j++))
    do
        for ((k=1; k<=3;k++))
        do
        {
            curl --connect-timeout 1 --max-time 1 "http://cluster${k}:9200/test/default/_search?q=${field}%3A${i}x${j}"
            echo ""
        }&
        done
        wait
    done
}&
done
