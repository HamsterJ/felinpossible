#!/usr/bin/ksh

mysql --database=felinpossible --user=felinpossible --password=TAG_PASSWORD_DB < $HOME/crontab/sql/updateFichesCat.sql 
mysql --database=felinpossible --user=felinpossible --password=TAG_PASSWORD_DB < $HOME/crontab/sql/updateFa.sql
