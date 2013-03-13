AWS-DynamoDB-to-MongoDB
=======================

After inquiring with folks in #AWS and #MongoDB, I could not find an existing way to export a Dynamo table to Mongo. This method worked for me:

[Set up an AWS EMR or Data Pipeline job to export the DynamoDB table.](http://docs.aws.amazon.com/datapipeline/latest/DeveloperGuide/dp-importexport-ddb-part2.html)


Then run the following sed line to convert the AWS export to valid JSON:
```bash
sed -e 's/$/}/' -e $'s/\x02/,"/g' -e $'s/\x03/":/g' -e 's/^/{"/' <exported_table> > <exported_table>.json
```

And import with:
```bash
mongoimport -d <database> -c <collection> <exported_table>.json;
```
