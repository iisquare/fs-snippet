#!/bin/bash
curl -X POST -H "Content-type:application/json" -d '{"query":{"match_all":{}}}' "http://cluster1:9200/test/default/_delete_by_query"
echo "Done!"

