# Create-AWS-environment
Bash Script to Create and Destroy AWS Enviroment

To run this script you will need to pass 5 total Variables:

EX: ./create-env.sh ami-cea009ae Jiggy sg-91e12ae8 apache-conf 3

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
