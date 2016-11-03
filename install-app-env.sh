#!/bin/bash
echo "Lets create our database instances!"
aws rds create-db-instance --db-instance-identifier dbfirst --allocated-storage 5 --db-instance-class db.t2.micro --engine mysql --master-username jpatel74 --master-user-password Pat3l120133
echo "Lets wait until the database instance is avaliable. Be aware can take up to 10 mins!"
aws rds wait db-instance-available
echo "--db-instance-identifier: dbfirst | --master-username : jpatel74 | --master-user-password : P@t3l120133"
URL=`aws rds describe-db-instances --query 'DBInstances[*].[Endpoint.Address]'`
echo "Your Database URL is: $URL"
#echo "$hostname = '$URL'" >> /website/password.php
echo "Creating SNS topic named my-topic"
SNS=`aws sns create-topic --name my-topic`
aws sns subscribe --topic-arn $SNS --protocol email --notification-endpoint jpatel74@hawk.iit.edu
echo "Creating a queue"
aws sqs create-queue --queue-name MyQueue
echo "Creating buckets"
aws s3api create-bucket --bucket raw-jjp --acl public-read --region us-east-2
aws s3api create-bucket --bucket finish-jjp --acl public-read --region us-east-2
dbfirst.ccct71t5l9fe.us-west-2.rds.amazonaws.com:3306
