#!/usr/bin/ksh

mysql --database=felinpossible --user=felinpossible --password=nxFjNu3f7CfQ4ecs < ./sql/updateFichesCat.sql 
mysql --database=felinpossible --user=felinpossible --password=nxFjNu3f7CfQ4ecs < ./sql/updateFa.sql

echo "CAT UPDATED:" `date +"%m/%d/%Y %H:%M:%S"` >> log.log