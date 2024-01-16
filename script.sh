#!/bin/bash


while true
do
	sleep 600
	bash /var/www/html/awdata/main.sh 2>&1 | /var/www/html/awdata/timestamp.sh >> /var/www/html/awdata/camaranms_log.log
done
