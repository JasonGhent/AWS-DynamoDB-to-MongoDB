AWS DynamoDB to MongoDB Conversion
==================================

I have been working with a company that stores NoSQL data as JSON strings in AWS's DynamoDB. We recently decided to perform some more complicated analysis of our data with MongoDB.

After inquiring with folks in #AWS and #MongoDB, I could not find an existing way to export a Dynamo table to Mongo. 

This method worked for me with our existing Data Pipeline backups ([Set up an AWS EMR or Data Pipeline job to export the DynamoDB table](http://docs.aws.amazon.com/datapipeline/latest/DeveloperGuide/dp-importexport-ddb-part2.html)) instead of performing queries against the Dynamo DB at the cost of additional throughput.


### Converting to JSON
The following sed line was used to convert an AWS Pipeline export to valid JSON, thereby removing the junk unicode characters:

```bash
sed -e 's/$/}/' -e $'s/\x02/,"/g' -e $'s/\x03/":/g' -e 's/^/{"/' <exported_table> > <exported_table>.json
```


### Migrating to MongoDB
This method was originally used to recreate the initial string-bound structure held by our AWS stores. However, with Mongo we can actually store the entire nested JSON object properly. I've included the PHP script I used to do so, but it is a very straight-forward operation that can be performed by any scripting language with a JSON parser.

You will need to run:

```bash
cat <exported_table>.json | php -q aws_converter.php > mongo_formatted.json
mongoimport -d <database> -c <collection> <exported_table>.json;
```
