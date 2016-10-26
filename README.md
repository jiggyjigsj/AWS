# Create-AWS-environment
Bash Script to Create and Deestroy AWS Enviroment

To run this script you will need to pass 5 total Variables:

EX: ./create-env.sh ami-06b94666 Jiggy sg-91e12ae8 apache-conf 3

## Name           /          Default Value
1. AMI ID /		 ami-98d97ef8
2. key-name	/	 Jiggy
3. security-group	/ sg-91e12ae8
4. launch-configuration / apache-conf
5. count		/ 3

*****************************************************************

To destroy your enviroment, you will need to pass 3 variables:

Ex. ./destroy-env.sh apache-auto apache-conf apache-lb

## Name           /          Default Value
1. Auto-Scaling name / apache-auto
2. Launch-Configuration / apache-conf
3. Load Balancer Name / apache-lb
