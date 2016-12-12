# Create-AWS-environment
Bash Script to Create and Destroy AWS Enviroment

To run this script you will need to pass 5 total Variables:

EX: ./install-env.sh ami-cea009ae Jiggy sg-91e12ae8 apache-conf 3
Then run ./install-app-env.sh to luach the rest of the infrastructure

## Name           /          Default Value
1. AMI ID /		 ami-cea009ae
2. key-name	/	 Jiggy
3. security-group	/ sg-91e12ae8
4. launch-configuration / apache-conf
5. count		/ 3

*****************************************************************

To destroy your enviroment:

Ex. ./destroy-env.sh



Credit:

Side by Side script taken from http://zurb.com/playground/twentytwenty
