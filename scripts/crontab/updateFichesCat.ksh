#!/usr/bin/ksh
#echo "BEGIN UPDATE:" `date +"%m/%d/%Y %H:%M:%S"` >> /var/www/html/scripts/crontab/log.log
mysql --database=felinpossible --user=felinpossible --password=<password> < /var/www/html/scripts/crontab/sql/updateFichesCat.sql 
mysql --database=felinpossible --user=felinpossible --password=<password> < /var/www/html/scripts/crontab/sql/updateFa.sql
