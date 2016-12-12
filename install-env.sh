#!/bin/bash
if [ -z ${5+x} ]; 
then 
	echo "Error You didn't provide all the needed Variables!"
	echo "Look at the example needed below"
	echo "EX: ./install-env.sh ami-7d6bc21d Jiggy sg-91e12ae8 apache-conf 3
	   NAME                  Default Value
	1. AMI ID 		 ami-7d6bc21d
	2. key-name		 Jiggy
	3. security-group	 sg-91e12ae8
	4. launch-configuration  apache-conf
	5. count		 3";
else 
	echo "Creating a load balancer"
	LBNAME=`aws elb create-load-balancer --load-balancer-name apache-lb --listeners Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80 --subnets subnet-88f6acec --security-groups sg-91e12ae8`
	sleep 2
	echo "Creating Launch Config"
	aws autoscaling create-launch-configuration --launch-configuration-name $4 --image-id $1 --security-groups $3 --key-name $2 --instance-type t2.micro --iam-instance-profile developer --user-data file://install-app.sh
	sleep 2
	echo "Creating auto scaling group"
	aws autoscaling create-auto-scaling-group --auto-scaling-group-name apache-auto --launch-configuration apache-conf --availability-zone us-west-2b --load-balancer-name apache-lb --max-size 5 --min-size 0 --desired-capacity $5
	echo "Attaching Load balancer and launching instances"
	aws autoscaling attach-load-balancers --load-balancer-names apache-lb --auto-scaling-group-name apache-auto
	echo "Lets wait for the instances to run"
	ID=
	ID=`aws ec2 describe-instances --query 'Reservations[*].Instances[*].[Placement.AvailabilityZone, State.Name, InstanceId]' | grep -E 'running|pending' | awk '{print $3}'`
	aws ec2 wait instance-running --instance-ids $ID
	sleep 15
	echo "Load balancers live on: " $LBNAME
	echo "But please wait atleast a minute before accessing"
echo "Creating worker instance"
aws ec2 run-instances --image-id $1 --security-group-ids $3 --key-name $2 --instance-type t2.micro --iam-instance-profile Name=developer --user-data file://install-app-worker.sh --placement AvailabilityZone=us-west-2b
fi
