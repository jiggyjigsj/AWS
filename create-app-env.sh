#!/bin/bash
echo "Lets create our database instances!"
aws rds create-db-instance --db-instance-identifier dbfirst --allocated-storage 5 --db-instance-class db.t2.micro --engine mysql --master-username jpatel74 --master-user-password P@t3l120133
echo "Lets wait until the database instance is avaliable. Be aware can take up to 10 mins!"
aws rds wait db-instance-available
echo "--db-instance-identifier: dbfirst | --master-username : jpatel74 | --master-user-password : P@t3l120133"

