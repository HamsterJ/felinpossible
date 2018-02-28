#!/usr/bin/ksh
#Variables
typeset -i NB_FILES_TO_KEEP
typeset -i NB_FILES
NB_FILES_TO_KEEP=11
<<<<<<< HEAD:scripts/saveDatabase.ksh
echo "BEGIN SAVE:" `date +"%m/%d/%Y %H:%M:%S"` >> /var/www/html/scripts/crontab/log.log
=======

>>>>>>> parent of 25e13c93... Adaptations suite changement de serveur prod:scripts/crontab/saveDatabase.ksh
#######
# Dump
#######
perl /home/www/felinpossible.fr/mysqlDumper/msd_cron/crondump.pl -config=mysqldumper.conf.php -html_output=0

##########
# Compress
##########
for file in $(ls /home/www/felinpossible.fr/mysqlDumper/work/backup/*.sql 2>/dev/null)
do
  echo "\nCompression de $file..."
  gzip $file
  echo "\nCompression terminee"
done

##################
# Delete old files
##################
NB_FILES=$(ls -lt /home/www/felinpossible.fr/mysqlDumper/work/backup/*.gz |wc -l)
echo "NB_FILES : $NB_FILES"
if [[ ${NB_FILES} != "" && ${NB_FILES} -ge ${NB_FILES_TO_KEEP} ]]
then
  FILE_TO_DELETE=$(ls -t /home/www/felinpossible.fr/mysqlDumper/work/backup/*.gz | tail -1)
  echo "delete file : $FILE_TO_DELETE"
  rm $FILE_TO_DELETE
fi
<<<<<<< HEAD:scripts/saveDatabase.ksh
echo "END SAVE:" `date +"%m/%d/%Y %H:%M:%S"` >> /var/www/html/scripts/crontab/log.log
=======
>>>>>>> parent of 25e13c93... Adaptations suite changement de serveur prod:scripts/crontab/saveDatabase.ksh
