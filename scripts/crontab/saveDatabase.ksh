#!/usr/bin/ksh
#Variables
typeset -i NB_FILES_TO_KEEP
typeset -i NB_FILES
NB_FILES_TO_KEEP=11
echo "BEGIN SAVE:" `date +"%m/%d/%Y %H:%M:%S"` >> log.log
#######
# Dump
#######
perl /var/www/html/mysqlDumper/msd_cron/crondump.pl -config=mysqldumper.conf.php -html_output=0

##########
# Compress
##########
for file in $(ls /var/www/html/mysqlDumper/work/backup/*.sql 2>/dev/null)
do
  echo "\nCompression de $file..."
  gzip $file
  echo "\nCompression terminee"
done

##################
# Delete old files
##################
NB_FILES=$(ls -lt /var/www/html/mysqlDumper/work/backup/*.gz |wc -l)
echo "NB_FILES : $NB_FILES"
if [[ ${NB_FILES} != "" && ${NB_FILES} -ge ${NB_FILES_TO_KEEP} ]]
then
  FILE_TO_DELETE=$(ls -t /var/www/html/mysqlDumper/work/backup/*.gz | tail -1)
  echo "delete file : $FILE_TO_DELETE"
  rm $FILE_TO_DELETE
fi
echo "END SAVE:" `date +"%m/%d/%Y %H:%M:%S"` >> log.log