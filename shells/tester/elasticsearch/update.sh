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
            {
                curl -X POST -H "Content-type:application/json" --request PUT -d '{"id":"'${id}'","title":"'${title}'"}' "http://cluster${k}:9200/test/default/${id}"
                echo ""
            }&
        done
        wait
    done
}&
done
wait
echo "Done!"

