#!/bin/bash
field="title"
for ((i=1; i<=9;i++))
do
{
    for ((j=1; j<=100000;j++))
    do
        for ((k=1; k<=3;k++))
        do
            id="${i}_${j}_${RANDOM}"
            title=$(date +%s%N)
            curl -X POST -H "Content-type:application/json" -d '{"add":{ "doc":{"id":"'${id}'","'${field}'":"'${title}'"},"boost":1.0,"overwrite":true,"commitWithin":1000}}' "http://cluster${k}:8080/solr/test/update?wt=json&commit=true" &
        done
        wait
    done
}&
done
wait
echo "Done!"

