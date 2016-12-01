#!/bin/bash
echo "
             \|/
            .-*-         
           / /|\         
          _L_            
        ,"   ".          
    (\ /  O O  \ /)      
     \|    _    |/       
       \  (_)  /         
       _/.___,\_         
      (_/     \_)         
"
echo "GET TO THE CHOPPA!!!"
#Setting Autoscaling name
AutoName=`aws autoscaling describe-auto-scaling-groups --query 'AutoScalingGroups[*].[AutoScalingGroupName]'`
#Checking if autscale exist
if [ -z "$AutoName" ];
then
echo "No Instances Found"
else
echo "Updating autoscaling group"
aws autoscaling update-auto-scaling-group --auto-scaling-group-name $AutoName --min-size 0 --max-size 0 --desired-capacity 0
fi
ID=
ID=`aws ec2 describe-instances --query 'Reservations[*].Instances[*].[Placement.AvailabilityZone, State.Name, InstanceId]' | grep -E 'running|pending' | awk '{print $3}'`
if [ -z "$ID" ];
then
echo "No Additional Instances Found"
else
echo "Here are all running instances: " $ID
aws ec2 terminate-instances --instance-ids $ID
sleep 5
echo "Waiting till instances terminate"
aws ec2 wait instance-terminated
fi
if [ -z "$AutoName" ];
then
echo "No Auto Scaling Group Found"
else
echo "Detaching load balancer"
aws autoscaling detach-load-balancers --load-balancer-names apache-lb --auto-scaling-group-name $AutoName
echo "Lets Sleep for 30 Sec. just to be safe here"
sleep 30
echo "Deleting auto scaling group"
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $AutoName
fi
LCName=`aws autoscaling describe-launch-configurations --query 'LaunchConfigurations[*].[LaunchConfigurationName]'`
if [ -z "$LCName" ];
then
echo "No Launch Configuration found"
else
echo "Deleting launch configuration"
aws autoscaling delete-launch-configuration --launch-configuration-name $LCName
fi
LBName=`aws elb describe-load-balancers --query 'LoadBalancerDescriptions[*].[LoadBalancerName]'`
if [ -z "$LBName" ];
then
echo "No Load Balancer Found"
else
echo "Deleting Load Balancer"
aws elb delete-load-balancer --load-balancer-name $LBName
fi
DBName=`aws rds describe-db-instances --query 'DBInstances[*].[DBInstanceIdentifier]'`
if [ -z "$DBName" ];
then
echo "No Load Balancer Found"
else
echo "Deleting Database"
aws rds delete-db-instance --skip-final-snapshot --db-instance-identifier $DBName
fi
TopicName=`aws sns list-topics --query 'Topics[*].[TopicArn]'`
if [ -z "$TopicName" ];
then
echo "No Topics Found"
else
echo "Deleting Topic"
aws sns delete-topic --topic-arn "$TopicName"
fi
QueueURLS=`aws sqs list-queues --query 'QueueUrls[*]'`
if [ -z "$QueueURLS" ];
then
echo "No Queues Found"
else
echo "Deleting Queue"
aws sqs delete-queue --queue-url $QueueURLS
fi
Buckets=`aws s3api list-buckets --query 'Buckets[].Name'`
if [ -z "$Buckets" ];
then
echo "No Buckets Found"
else
aws s3api delete-bucket --bucket $Buckets --region us-east-2
fi
echo "If you didn't didn't get to the CHOPA by now its too late!!!"
echo "
                  ..-^~~~^-..
                .~           ~.
               (;:           :;)
                (:           :)
                  ':._   _.:'
                      | |
                    (=====)
                      | |
-O-                   | |
  \                   | |
  /\               ((/   \))"
#Art taken from: http://www.chris.com/ascii/index.php?art=objects/explosives
