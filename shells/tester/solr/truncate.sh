#!/bin/bash
curl -X POST -H "Content-type:application/json" -d '{"delete":{ "query":"*:*"}}' "http://cluster1:8080/solr/test/update?wt=json&commit=true"
echo "Done!"

