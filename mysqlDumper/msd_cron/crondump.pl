#!/usr/bin/perl -w
########################################################################################
# MySQLDumper CronDump
#
# 2004,2005 by Steffen Kamper, Daniel Schlichtholz
# additional scripting: Detlev Richter
#
# for support etc. visit http://www.mysqldumper.de/board
# (c) GNU General Public License
########################################################################################
# Script-Version
$pcd_version="1.23";

########################################################################################
# please enter the absolute path of the config-dir
# when calling the script without parameters the default_configfile (mysqldumper.conf.php) will be loaded
# e.g. - (zum Beispiel): 
#my $absolute_path_of_configdir="/home/www/doc/8176/mysqldumper.de/www/mysqldumper/work/config/";
#
my $absolute_path_of_configdir="/home/www/felinpossible.fr/mysqlDumper/work/config/";
my $cgibin_path=""; # this is needed for MIME::Lite if it is in cgi-bin
my $default_configfile="mysqldumper.conf.php";

########################################################################################
# nothing to edit under this line !!!
########################################################################################
# import the necessary modules ...
use strict;
use DBI;
use File::Find;
use File::Basename;
use CGI;

########################################################################################
use vars qw(
$pcd_version  $dbhost  $dbname  $dbuser  $dbpass $dbport
$cron_save_all_dbs $cron_db_array $cron_dbpraefix_array $dbpraefix $command_beforedump_array $command_afterdump_array
$compression  $backup_path $logdatei  $completelogdatei $nl $command_beforedump $command_afterdump
$cron_printout  $cronmail  $cronmail_dump $cronmailto $cronmailto_cc $cronmailfrom
$cronftp $mp $multipart_groesse $email_maxsize
$auto_delete $cron_del_files_after_days $max_backup_files $max_backup_files_each $perlspeed $optimize_tables_beforedump $result
@key_value $pair $key $value $conffile @confname $logcompression $log_maxsize $complete_log 
$starttime $Sekunden $Minuten $Stunden $Monatstag $Monat $Jahr $Wochentag $Jahrestag $Sommerzeit
$ri $rct $tabelle  @tables @tablerecords $dt $sql_create @ergebnis @ar $sql_daten $inhalt
$insert $totalrecords $error_message $cfh $oldbar $print_out $msg $dt $ftp $dateistamm $dateiendung
$mpdatei $i $BodyNormal $BodyMultipart $BodyToBig $BodyNoAttach $BodyAttachOnly $Body $DoAttach $cmt $part $fpath $fname
$fmtime $timenow $daydiff $datei $inh $gz $search $fdbname @str @dbarray $item %dbanz $anz %db_dat 
$fieldlist $first_insert $my_comment $sendmail_call $config_read_from
$cron_smtp $cron_smtp_port $cron_use_sendmail $v1 $v2 
@ftp_transfer @ftp_timeout @ftp_user @ftp_pass @ftp_dir @ftp_server @ftp_port @ftp_mode
$output $query $skip $html_output $datei
@trash_files $time_stamp @filearr $sql_file $backupfile $memory_limit $dbh $sth @db_array
@dbpraefix_array @db_command_beforedump_array @db_command_afterdump_array $db_anz
$record_count $filesize $status_start $status_end $sql_text $punktzaehler @backupfiles_name
@backupfiles_size $mysql_commentstring $character_set $mod_gz $mod_mime $mod_ftp
%filearray
);

$memory_limit=100000;
$mysql_commentstring="-- ";
$character_set="utf8";
$sql_text='';
$sql_file='';
$punktzaehler=0;
@trash_files=();
@filearr=();

# import the optional modules ...
my $eval_in_died;
$mod_gz=0;
$mod_ftp=0;
$mod_mime=0;
push (@INC, "$cgibin_path");

eval { $eval_in_died = 1; require Compress::Zlib; };
if(!$@){
	$mod_gz = 1;
	import Compress::Zlib;
}
eval { $eval_in_died = 1; require Net::FTP; };
if(!$@){
	$mod_ftp = 1;
	import Net::FTP;
}
eval { $eval_in_died = 1; require MIME::Lite; };
if(!$@){
	$mod_mime = 1;
	import MIME::Lite;
}

use CGI::Carp qw(warningsToBrowser fatalsToBrowser);  
warningsToBrowser(1);

#include config file
$conffile="";

use Getopt::Long;
GetOptions ("config=s" => \$conffile, 
            "html_output=s"  => \$html_output);
if (!defined $html_output) { $html_output=0; };	# suppress HTML Output 

if(trim($conffile) ne "") 
{
	$config_read_from="shell";
}

if($ENV{QUERY_STRING}) {
	$html_output=1; # turn HTML Output on if called via Browser-Request
	my $querystring=$ENV{QUERY_STRING};
	#$querystring=~ s/\?/ /g;
	@key_value = split(/&/,$querystring);
	foreach $pair(@key_value)
	{
		#$pair =~ tr/+/ /;
		($key,$value) = split(/=/,$pair);
		if($key eq "config")
		{
			$value=~ s/\?/ /g;
			$conffile=$value;
			$config_read_from="Querystring";
		}
		if($key eq "html_output") { $html_output=$value; }; # overwrite var if given in call
	}
}


$conffile=trim($conffile);
if($conffile eq "") 
{
	$conffile=$default_configfile; # no Parameter for configfile given -> use standardfile "mysqldumper.php.conf"
	$config_read_from="standard configuration";
}


# Security: try to detect wether someone tries to include some external configfile
die "Hacking attempt - I wont do anything!\nGo away\n\n" if (lc($conffile) =~ m /:/);

# check config-dir
$absolute_path_of_configdir=trim($absolute_path_of_configdir); # remove spaces
opendir(DIR, $absolute_path_of_configdir) or die "The config-directory you entered is wrong !\n($absolute_path_of_configdir - $!) \n\nPlease edit the crondump.pl and enter the right configuration-path.\n\n";
closedir(DIR);
my $abc=length($absolute_path_of_configdir)-1;
my $defed=substr($absolute_path_of_configdir,$abc,1);
if($defed ne "/") {
	$absolute_path_of_configdir=$absolute_path_of_configdir."/";
}

if (substr($conffile,length($conffile)-9,9) ne '.conf.php') { $conffile.='.conf.php'; };

$datei=$absolute_path_of_configdir.$conffile;

open(CONFIG,"<$datei") or die "\nI couldn't open the configurationfile:".$datei."\nFile not found or not accessible!\n\n";
while (my $line = <CONFIG>)
{
	chomp($line);
	if ($line ne '<?php' && $line ne '1;' && substr($line,0,2) ne '?>' && substr($line,0,1) ne '#')
	{
		eval($line);
	}
}
close(CONFIG);

if ($html_output==1) { $cron_printout=1; }; # overwrite output if HTML-Output is activated

@confname=split(/\//,$conffile);
# Output Headers
PrintHeader();

PrintOut("<span style=\"color:#0000FF;\">Configurationfile '".$conffile."' was loaded successfully from ".$config_read_from." .</span>");
if($mod_gz==1) {
	PrintOut("<span style=\"color:#0000FF;\">Compression Library loaded successfully...</span>");
} else {
	$compression=0;
	PrintOut("<span style=\"color:red;\">Compression Library loading failed - Compression deactivated ...</span>");
}
if($mod_ftp==1) {
	PrintOut("<span style=\"color:#0000FF;\">FTP Library loaded successfully...</span>");
} else {
	$cronftp=0;
	PrintOut("<span style=\"color:red;\">FTP Library loading failed - FTP deactivated ...</span>");
}
if($mod_mime==1) {
	PrintOut("<span style=\"color:#0000FF;\">Mail Library loaded successfully...</span>");
} else {
	$cronmail=0;
	PrintOut("<span style=\"color:red;\">Mail Library loading failed - Mail deactivated ...</span>");
}


# SignalHandler einrichten für Browser-Stop-Button,
# unterbrochene Socketverbindungen, Crtl-C
# Datenbankverbindung wird dann noch ordnungsgemäss geschlossen.
$SIG{HUP} = $SIG{PIPE} = $SIG{INT} =\&closeScript;


#teste Zugriff auf logfile
write_log("Starting backup using Perlscript version $pcd_version (configuration $conffile)\n");

#Auto-Delete ausführen
if($auto_delete>0) {
	#Autodelete Days
	if($cron_del_files_after_days>0) {
		PrintOut("Autodelete: search for backups older than <font color=\"#0000FF\">$cron_del_files_after_days</font> days ...");
		find(\&AutoDeleteDays, $backup_path);
		DeleteFiles (\@trash_files);
	}
	#Autodelete Count
	if($max_backup_files>0) {
		PrintOut("Autodelete: search for more backups than <font color=\"#0000FF\">$max_backup_files</font>");
		find(\&AutoDeleteCount, $backup_path);
		DoAutoDeleteCount();
		DeleteFiles (\@trash_files);
	}
}

#Jetzt den Dump anschmeissen
# mal schauen, obs mehrere DB's sind
if($cron_save_all_dbs==0) 
{
	$command_beforedump=$command_beforedump_array;
	$command_afterdump=$command_afterdump_array;
	$dbpraefix_array[0]=$dbpraefix;
	ExecuteCommand(1);
	DoDump();
	ExecuteCommand(2);
	PrintOut("<strong>Crondump finished.</strong>");
	closeScript();
}  
else 
{
	@db_array=split(/\|/,$cron_db_array);
	@dbpraefix_array=split(/\|/,$cron_dbpraefix_array);
	@db_command_beforedump_array=split(/\|/,$command_beforedump_array);
	@db_command_afterdump_array=split(/\|/,$command_afterdump_array);
	$db_anz=@db_array;
	for(my $ii = 0; $ii < $db_anz; $ii++) {
		if ($mp>0) { $mp=1; } # Part-Reset if using Multipart (for next database)
		$dbname=$db_array[$ii];
		$dbpraefix=($dbpraefix_array[$ii]) ? $dbpraefix_array[$ii] : "";
		$command_beforedump=($db_command_beforedump_array[$ii]) ? $db_command_beforedump_array[$ii] : "";
		$command_afterdump=($db_command_afterdump_array[$ii]) ? $db_command_afterdump_array[$ii] : "";
		PrintOut("<br>Starting backup <strong>".($ii+1)."</strong> of <strong>$db_anz</strong> (database <strong>`$dbname`</strong> ".(($dbpraefix ne "") ? "- looking for tables with prefix '<span style=\"color:blue\">$dbpraefix</span>')" : ")"));
		ExecuteCommand(1);
		DoDump();
		ExecuteCommand(2);
	}
	PrintOut("<strong>ALL $db_anz BACKUPS ARE DONE!!!</strong>");
	$cron_save_all_dbs=2;
	closeScript();
}
if ($html_output==0) { print "\nEnd of Cronscript"; }

##############################################
# Subroutinen                                #
##############################################
sub DoDump {
	($Sekunden, $Minuten, $Stunden, $Monatstag, $Monat, $Jahr, $Wochentag, $Jahrestag, $Sommerzeit) = localtime(time);
	$Jahr+=1900;$Monat+=1;$Jahrestag+=1;
	my $CTIME_String = localtime(time);
	$time_stamp=$Jahr."_".sprintf("%02d",$Monat)."_".sprintf("%02d",$Monatstag)."_".sprintf("%02d",$Stunden)."_".sprintf("%02d",$Minuten);
	$starttime= sprintf("%02d",$Monatstag).".".sprintf("%02d",$Monat).".".$Jahr."  ".sprintf("%02d",$Stunden).":".sprintf("%02d",$Minuten);
	$fieldlist="";
	# Verbindung mit MySQL herstellen, $dbh ist das Database Handle
	
	$dbh = DBI->connect("DBI:mysql:$dbname:$dbhost:$dbport","$dbuser","$dbpass") || die   "Database connection not made: $DBI::errstr"; 
	# herausfinden welche Mysql-Version verwendet wird
	$sth = $dbh->prepare("SELECT VERSION()");
	$sth->execute;
	my @mysql_version=$sth->fetchrow;
	my @v=split(/\./,$mysql_version[0]);
	if ($v[0]>3)
	{
		$sth = $dbh->prepare("SET NAMES '".$character_set."'");
		$sth->execute;
	}

	if($v[0]>=5 || ($v[0]>=4 && $v[1]>=1) )
	{
		#mysql Version >= 4.1
		%db_dat = (name => 0,
		rows => 4,
		data_length =>6,
		index_length =>8,
		update_time =>11 );
		# get standard encoding of MySQl-Server
		$sth = $dbh->prepare("SHOW VARIABLES LIKE 'character_set_connection'");
		$sth->execute;
		@ar=$sth->fetchrow; 
		$character_set=$ar[1];
	}
	else
	{
		#mysql Version < 4.1
		%db_dat = (name => 0,
		rows => 3,
		data_length =>5 ,
		index_length =>7,
		update_time =>11 );
		# get standard encoding of MySQl-Server
		$sth = $dbh->prepare("SHOW VARIABLES LIKE 'character_set'");
		$sth->execute;
		@ar=$sth->fetchrow; 
		$character_set=$ar[1];
	}
	PrintOut("Characterset of connection set to <strong>".$character_set."</strong>.");

	
	#Statuszeile erstellen
	my $t=0;
	my $r=0;
	my $query="SHOW TABLE STATUS FROM `$dbname`";
	if ($dbpraefix ne "") 
	{ 
		$query.=" LIKE '$dbpraefix%'"; 
		PrintOut("Searching for tables inside database <strong>`$dbname`</strong> with prefix <strong>'$dbpraefix'</strong>"); 
	} 
	else
	{
		PrintOut("Searching for tables inside database <strong>`$dbname`</strong>"); 
	}
	$sth = $dbh->prepare($query);
	$sth->execute;
	my $st_e="\n";
	#Arrays löschen
	undef(@tables);
	undef(@tablerecords);
	my $opttbl=0;

	$st_e.="-- TABLE-INFO";
	while ( @ar=$sth->fetchrow) {
		if($optimize_tables_beforedump==1) {
			#tabelle optimieren
			my $sth_to = $dbh->prepare("OPTIMIZE Table `$ar[$db_dat{name}]`");
			$sth_to->execute; $opttbl++;
		}
		if(!defined $ar[$db_dat{update_time}]) { $ar[$db_dat{update_time}]=0; }
		if($dbpraefix eq "")
		{
			$t++;
			$r+=$ar[$db_dat{rows}];
			push(@tables,$ar[$db_dat{name}]);
			push(@tablerecords,$ar[$db_dat{rows}]);
			$st_e.=$mysql_commentstring."TABLE\|$ar[$db_dat{name}]\|$ar[$db_dat{rows}]\|".($ar[$db_dat{data_length}]+$ar[$db_dat{index_length}])."\|$ar[$db_dat{update_time}]\n";
		}
		else
		{
			if (substr($ar[$db_dat{name}],0,length($dbpraefix)) eq $dbpraefix) 
			{
				$t++; 
				$r+=$ar[$db_dat{rows}];
				push(@tables,$ar[$db_dat{name}]);
				push(@tablerecords,$ar[$db_dat{rows}]);
				$st_e.=$mysql_commentstring."TABLE\|$ar[$db_dat{name}]\|$ar[$db_dat{rows}]\|".($ar[$db_dat{data_length}]+$ar[$db_dat{index_length}])."\|$ar[$db_dat{update_time}]\n";
			}
		}
	}
	$st_e.="-- EOF TABLE-INFO";
	
	PrintOut("<span style=\"color:blue;font-size:11px;\">$opttbl tables have been optimized</span>") if($opttbl>0) ;
	PrintOut("Found $t tables with $r records.");

	#AUFBAU der Statuszeile:
	#	-- Status:tabellenzahl:datensätze:Multipart:Datenbankname:script:scriptversion:Kommentar:MySQLVersion:Backupflags:SQLBefore:SQLAfter:Charset:EXTINFO
	#	Aufbau Backupflags (1 Zeichen pro Flag, 0 oder 1, 2=unbekannt)
	#	(complete inserts)(extended inserts)(ignore inserts)(delayed inserts)(downgrade)(lock tables)(optimize tables)
	#
	$status_start=$mysql_commentstring."Status:$t:$r:";
	my $downgrade=0;
	my $flags="1$optimize_tables_beforedump";
	$status_end=":$dbname:perl:$pcd_version:$my_comment:$mysql_version[0]:$flags";
	$status_end.=":$command_beforedump:$command_afterdump:$character_set:EXTINFO$st_e\n".$mysql_commentstring."Dump created on $CTIME_String by PERL Cron-Script\n".$mysql_commentstring."Dump by MySQLDumper (http://www.mysqldumper.de/board/)\n\n";


	if($mp>0) {
		$sql_text=$status_start."MP_$mp".$status_end;
	} else {
		$sql_text=$status_start.$status_end;
	}
	NewFilename();
	
	$totalrecords=0;
	my $skip=0;
	for (my $tt=0;$tt<$t;$tt++) {
		$tabelle=$tables[$tt];
		$skip=0;
		# definition auslesen
		if($dbpraefix eq "" or ($dbpraefix ne "" && substr($tabelle,0,length($dbpraefix)) eq $dbpraefix)) {
			PrintOut("Dumping table `<strong>$tabelle</strong>` ");
			$a="\n\n$mysql_commentstring\n$mysql_commentstring Create Table `$tabelle`\n$mysql_commentstring\n\nDROP TABLE IF EXISTS `$tabelle`;\n";
			$sql_text.=$a;
			$sql_create="SHOW CREATE TABLE `$tabelle`";
			$sth = $dbh->prepare($sql_create);
			$sth->execute;
			@ergebnis=$sth->fetchrow;
			$sth->finish;
			$a=$ergebnis[1].";\n";
			if (length($a)<10)
			{
				write_log("Fatal error! Couldn't read CREATE-Statement of table `$tabelle`! This backup might be incomplete! Check your database for errors.");
				PrintOut("Fatal error! Couldn't read CREATE-Statement of table `$tabelle`! This backup might be incomplete! Check your database for errors.");
				$skip=1;
			}
			
			if ($skip==0)
			{
				$sql_text.=$a."\n$mysql_commentstring\n$mysql_commentstring Data for Table `$tabelle`\n$mysql_commentstring\n";
				$sql_text.="\n/*!40000 ALTER TABLE `$tabelle` DISABLE KEYS */;";

				WriteToFile($sql_text,0);
				$sql_text="";
				$punktzaehler=0;
				$fieldlist="(";
				$sql_create="SHOW FIELDS FROM `$tabelle`";
				$sth = $dbh->prepare($sql_create);
				$sth->execute;
				while ( @ar=$sth->fetchrow) {
					$fieldlist.="`".$ar[0]."`,";
				}
				$fieldlist=substr($fieldlist,0,length($fieldlist)-1).")";

				# daten auslesen
				$rct=$tablerecords[$tt];
				for (my $ttt=0;$ttt<$rct;$ttt+=$perlspeed) 
				{
					$insert = "INSERT INTO `$tabelle` $fieldlist VALUES (";
					$first_insert=0;
					$sql_daten="SELECT * FROM `$tabelle` LIMIT ".$ttt.",".$perlspeed.";";
					$sth = $dbh->prepare($sql_daten);
					$sth->execute;
					while ( @ar=$sth->fetchrow) 
					{
						#Start the insert
						if($first_insert==0) 
						{
							$a="\n$insert";
						}
						else
						{
							$a="\n(";
						}	
						foreach $inhalt(@ar) { $a.= $dbh->quote($inhalt).", "; }
						$a=substr($a,0, length($a)-2).");";
						$sql_text.= $a;
						if($memory_limit>0 && length($sql_text)>$memory_limit) 
						{
							WriteToFile($sql_text);
							$sql_text="";
							if($mp>0 && $filesize>$multipart_groesse) {NewFilename();}
						}
					}
				}
				$sql_text.="\n/*!40000 ALTER TABLE `$tabelle` ENABLE KEYS */;";
			}

			#jetzt wegschreiben
			WriteToFile($sql_text);
			$sql_text="";
			PrintOut("\n<br><em>$tablerecords[$tt] inserted records (size of backupfile now: ".byte_output($filesize).")</em>");
			$totalrecords+=$tablerecords[$tt];
			if($mp>0 && $filesize>$multipart_groesse) {NewFilename();}
		}
	}
	# Ende
	WriteToFile("\nSET FOREIGN_KEY_CHECKS=1;\n");
	WriteToFile($mysql_commentstring."EOB\n");
	PrintOut("\n<hr>Finished backup of database `$dbname`.");
	write_log("Finished backup of database `$dbname`.\n");

	# Jetzt der Versand per Mail
	if($cronmail==1) {
		PrintOut("sending mail ...");
		send_mail();
		write_log("Mail was sent to $cronmailto $cronmailto_cc succesfully\n");
		PrintOut("Mail was sent to $cronmailto $cronmailto_cc successfully.");
	}

	# Jetzt der Versand per FTP
	send_ftp();
}

#Wird aufgerufen, wenn Fehler passieren
sub err_trap {
	$error_message = shift(@_);
	PrintOut("Perl Cronscript ERROR: $error_message");
	exit(1);
}

sub PrintHeader {
	my $cgi = new CGI;
	print $cgi->header(-type => 'text/html; charset=utf-8');
	if ($html_output==1)
	{
		print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		print "<html><head><title>MySQLDumper - Perl CronDump [Version $pcd_version]</title>";
		print "<style type=\"text/css\">\nbody { padding:20px; } \n</style>";
		print "</head><body><h3>MySQLDumper - Perl CronDump [Version $pcd_version]</h3>\n";
	}
	else
	{
		#Mini-Ausgabe fuer externe Cronjob-Dienste, die eine kleine Rueckgabe erwarten
		print "MySQLDumper - Perl CronDump [Version $pcd_version] started successfully\n";
	}
	PrintOut("MySQLDumper - Perl CronDump [Version $pcd_version] started successfully");
}

sub PrintOut {
	$print_out = shift(@_);

	if (defined $print_out && length(trim($print_out))>0)
	{
		if($complete_log==1) {
			my $logsize=0;
			($Sekunden, $Minuten, $Stunden, $Monatstag, $Monat, $Jahr, $Wochentag, $Jahrestag, $Sommerzeit) = localtime(time);
			$Jahr+=1900; $Monat+=1;$Jahrestag+=1;
			$dt=sprintf("%02d",$Monatstag).".".sprintf("%02d",$Monat).".".sprintf("%02d",$Jahr)." ".sprintf("%02d",$Stunden).":".sprintf("%02d",$Minuten).":".sprintf("%02d",$Sekunden);
			if (-e $completelogdatei) {
				$logsize=(stat($completelogdatei))[7];
				unlink($completelogdatei) if($logsize + length($print_out)>$log_maxsize && $log_maxsize>0);
			}
			my $output=$print_out;
			#$output =~ s/<(.*?)>//gi;
			$output =~ s/\n//gi;
			$output =~ s/\r//gi;
			$output =~ s/<br>//gi;
			$output=trim($output);
			
			if ( ($logcompression==0) || ($mod_gz==0)) {
				open(DATEI,">>$completelogdatei") || err_trap('can\'t open mysqldump_perl.complete.log ('.$completelogdatei.').');
				print DATEI "$dt $output\n" || err_trap('can\'t write to mysqldump_perl.complete.log ('.$completelogdatei.').');
				close(DATEI)|| err_trap('can\'t close mysqldump_perl.complete.log ('.$completelogdatei.').');
				chmod(0777,$completelogdatei);
			} else {
				$gz = gzopen($completelogdatei, "ab") || err_trap("Cannot open mysqldump_perl.complete.log. ");
				$gz->gzwrite("$dt $output\n")  || err_trap("Error writing mysqldump_perl.complete.log. ");
				$gz->gzclose ;
				chmod(0777,$completelogdatei);
			}
		}
		if($cron_printout==1) {
			local ($oldbar) = $|;
			$cfh = select (STDOUT);
			$| = 1;
			if($html_output==0) 
			{
				$print_out =~ s/<(.*?)>//gi;
			}
			
			print $print_out;
			if ($html_output==1){ print "<br>\n"; } else { print "\n"; };
			$| = $oldbar;
			select ($cfh);
		}
	}
}

sub write_log {
	$msg = shift(@_);
	($Sekunden, $Minuten, $Stunden, $Monatstag, $Monat, $Jahr, $Wochentag, $Jahrestag, $Sommerzeit) = localtime(time);
	$Jahr+=1900; $Monat+=1;$Jahrestag+=1;
	$dt=sprintf("%02d",$Monatstag).".".sprintf("%02d",$Monat).".".sprintf("%02d",$Jahr)." ".sprintf("%02d",$Stunden).":".sprintf("%02d",$Minuten).":".sprintf("%02d",$Sekunden);

	my $logsize=0;
	if (-e $logdatei) {
		$logsize=(stat($logdatei))[7];
		unlink($logdatei) if($logsize+200>$log_maxsize && $log_maxsize>0);
	}

	if ( ($logcompression==0) || ($mod_gz==0)) {
		open(DATEI,">>$logdatei") || err_trap("can't open file ($logdatei).");
		print DATEI "$dt $msg" || err_trap("can't write to file ($logdatei).");
		close(DATEI)|| err_trap("can't close file ($logdatei).");
			chmod(0777,$logdatei);
	} else {
		$gz = gzopen($logdatei, "ab") || err_trap("Cannot open $logdatei.");
		$gz->gzwrite("$dt $msg")  || err_trap("Error writing $logdatei. ");
		$gz->gzclose ;
		chmod(0777,$logdatei);
	}
}

sub send_ftp {
	for(my $i = 0; $i <3; $i++)
	{
		if ($ftp_transfer[$i]==1)
		{
			PrintOut("sending ftp to $ftp_server[$i] ...");
			if ($ftp_timeout[$i]<1) { $ftp_timeout[$i]=30; };
			$ftp = Net::FTP->new($ftp_server[$i], Port => $ftp_port[$i], Timeout => $ftp_timeout[$i], Debug   => 1,Passive => $ftp_mode[$i]) or err_trap( "FTP-ERROR: Can't connect: $@\n");
			$ftp->login($ftp_user[$i], $ftp_pass[$i])   or err_trap("FTP-ERROR: Couldn't login\n");
			$ftp->binary();
			$ftp->cwd($ftp_dir[$i]) or err_trap("FTP-ERROR: Couldn't change directory: ".$ftp_dir[$i]);
			if($mp==0) 
			{
				$ftp->put($sql_file) or err_trap("FTP-ERROR: Couldn't put $sql_file\n");
				write_log("FTP-Transfer: transferring of `$backupfile` to $ftp_server[$i] finished successfully.\n");
				PrintOut("FTP-Transfer transferring `$backupfile` to $ftp_server[$i] was successful.");
			} 
			else 
			{
				$dateistamm=substr($backupfile,0,index($backupfile,"part_"))."part_";
				$dateiendung=($compression==1)?".sql.gz":".sql";
				$mpdatei="";
				for ($i=1;$i<$mp;$i++) 
				{
					$mpdatei=$dateistamm.$i.$dateiendung;
					$ftp->put($backup_path.$mpdatei)    or err_trap("Couldn't put $backup_path.$mpdatei\n");
					write_log("FTP-Transfer: transferring `$mpdatei` to $mpdatei finished successfully.\n");
					PrintOut("FTP-Transfer: transferring `$mpdatei` to $ftp_server[$i] was successful.");
				}
			}
		}
	}
}

sub send_mail {
	if ($cron_use_sendmail==1)
	{
		MIME::Lite->send("sendmail", $sendmail_call);
	}
	else
	{
		MIME::Lite->send('smtp', $cron_smtp, Timeout=>60);	
	}
	$BodyNormal='The attachement is your backup of your database `'.$dbname.'`.';
	$BodyMultipart="A multipart backup has been made.<br>You will receive one or more emails with the backup-files attached.<br>The database `".$dbname."` has been backupped.<br>The following files have been created:";
	$BodyToBig="The backup is bigger than the allowed max-limit of ".byte_output($email_maxsize)." and has not been attached.<br>Backup of database ".$dbname."<br><br>The following files have been created:";
	$BodyNoAttach="The backup has not been attached.<br>I saved your database `".$dbname."` to file<br>";
	$BodyAttachOnly="Here is your backup.";
	$Body="";
	$DoAttach=1;
	my @mparray;
	if($mp==0) {
		if(($email_maxsize>0 && $filesize>$email_maxsize) || $cronmail_dump==0) {
			if($cronmail_dump==0) {
				$Body=$BodyNoAttach.$backupfile." (".byte_output($filesize).")";
			} else {
				$Body=$BodyToBig.$backupfile." (".byte_output($filesize).")"
			}
			$DoAttach=0;
		} else {
			$Body=$BodyNormal;
		}
	} else {
		$Body=$BodyMultipart;
		$dateistamm=substr($backupfile,0,index($backupfile,"part_"))."part_";
		$dateiendung=($compression==1)?".sql.gz":".sql";
		$mpdatei="";
		for ($i=1;$i<$mp;$i++) {
			$mpdatei=$dateistamm.$i.$dateiendung;
			push(@mparray,"$mpdatei|$i");
			$Body.="$mpdatei (".(byte_output(stat($backup_path.$mpdatei)))[7]." )";
		}
	}
	$Body.="<br><br>Best regards,<br><br>MySQLDumper<br><a href=\"http://www.mysqldumper.de\">www.mysqldumper.de</a>";

	$msg = new MIME::Lite ;
	$msg = build MIME::Lite
	From    => $cronmailfrom ,
	To      => $cronmailto ,
	Cc	=> $cronmailto_cc,
	Subject => "MSD (Perl) - Backup of DB ".$dbname,
	Type    => 'text/html',
	Data    => $Body;
	if($DoAttach==1 && $mp==0) {
		attach $msg
		Type     => "BINARY" ,
		Path     => "$sql_file" ,
		Encoding => "base64",
		Filename => "$backupfile" ;
	}

	$msg->send || err_trap("Error sending mail !");
	
	if($DoAttach==1 && $mp>0 && $cronmail_dump>0) { 
		foreach $datei(@mparray) {
			@str=split(/\|/,$datei);
			$msg = new MIME::Lite ;
			$msg = build MIME::Lite
			From    => $cronmailfrom ,
			To      => $cronmailto ,
			Cc	=> $cronmailto_cc,
			Subject => "MSD (Perl) - Backup of DB $dbname File ".$str[1]." of ".@mparray ,
			Type    => 'text/html',
			Data    => $BodyAttachOnly;
			attach $msg
			Type     => "BINARY" ,
			Path     => $backup_path.$str[0] ,
			Encoding => "base64",
			Filename => $str[0] ;
			$msg->send || err_trap("Error sending mail !");
		}
	}
}


sub check_emailadr {
	$cmt = shift(@_);
	if ($cmt =~ /^([\w\-\+\.]+)@([\w\-\+\.]+)$/){ 
		return (1) 
	} else { 
		return (0) 
	}
}

sub NewFilename {
	$part="";
	if($mp>0) {
		$part="_part_$mp";
		$mp++;
	}
	if($compression==0) {
		$sql_file=$backup_path.$dbname."_".$time_stamp.$part.".sql";
		$backupfile=$dbname."_".$time_stamp.$part.".sql";
	} else {
		$sql_file=$backup_path.$dbname."_".$time_stamp.$part.".sql.gz";
		$backupfile=$dbname."_".$time_stamp.$part.".sql.gz";
	}
	if($mp==0) {
		PrintOut("\n<br>Starting to dump data into file <strong>`$backupfile`</strong>");
		write_log("Dumping data into file <strong>`$backupfile`</strong> \n");
	}
	if($mp==2) {
		PrintOut("\n<br>Starting to dump data into multipart-file </strong>`$backupfile`</strong>");
		write_log("Start Perl Multipart-Dump with file `$backupfile` \n");
	}
	if($mp>2) {
		PrintOut("\n<br>Continuing Multipart-Dump with file <strong>`$backupfile`</strong>");
		write_log("Continuing Multipart-Dump with file `$backupfile` \n");
	}
	if($mp>0) {
		$sql_text=$status_start."MP_".($mp-1).$status_end;
	} else {
		$sql_text=$status_start.$status_end;
	}
	$sql_text.="/*!40101 SET NAMES '".$character_set."' */;\n";
	$sql_text.="SET FOREIGN_KEY_CHECKS=0;\n";
	
	WriteToFile($sql_text,1);
	chmod(0644,$sql_file);
	$sql_text="";
	$first_insert=0;
	$punktzaehler=0;
	push(@backupfiles_name,$sql_file);
}

sub WriteToFile {
	$inh=shift;
	my $points=shift;
	if (!defined($points)) { $points=2; }
	
	if(length($inh)>0) {
		if($compression==0){
			open(DATEI,">>$sql_file");
			print DATEI $inh;
			close(DATEI);
		} else {
			$gz = gzopen($sql_file, "ab") || err_trap("Cannot open ".$sql_file);
			$gz->gzwrite($inh)  || err_trap("Error writing ".$sql_file);
			$gz->gzclose ;
		}
		if ($points>1)
		{
			if ($html_output==1) { print "<font color=red>.</font>"; } else { print "."; };
		}
		$filesize= (stat($sql_file))[7];
		$punktzaehler++;
		if($punktzaehler>120)
		{
			if ($html_output==1) { print "<br>"; } else { print "\n"; };
			$punktzaehler=0;
			PrintOut();
		}
	}
}

sub AutoDeleteDays {
	$fpath=$File::Find::name;
	$fname=basename($fpath);
	$fmtime=(stat($fname))[9];
	if($fmtime) {
		$timenow=time;
		$daydiff=($timenow-$fmtime)/60/60/24;
		if (((/\.gz$/) || (/\.sql$/)) && ($daydiff>$cron_del_files_after_days)) {
			PrintOut "$fpath $daydiff " if (-f $fname);
			push(@trash_files,$fpath);
		}
	}
}

sub AutoDeleteCount {
	$fpath=$File::Find::name;
	$fname=basename($fpath);
#	$fmtime=(stat($fname))[9];
#	if($fmtime) 
#	{
#		($Sekunden, $Minuten, $Stunden, $Monatstag, $Monat, $Jahr)=localtime($fmtime);
#		$Jahr+=1900; $Monat+=1;
#		$Monat="0".$Monat if(length($Monat)==1);
#		$search="_".$Jahr."_".$Monat."_";
#		$fdbname=substr($fname,0,index($fname,$search));
#		if ((/\.gz$/) || (/\.sql$/))  
#		{
#			push(@filearr,"$fdbname|$fmtime|$fpath");
#		}
#	}
	my @fileparts=split(/\./,$fname);
	#print "<br>Anzahl Elemente ";
	my $partcount=@fileparts;
	#print $anzahl;
	if ($partcount>1)
	{
		my $end=$fileparts[(@fileparts-1)];
		#print "<br>Filename $fname Ende: $end";

		# Read Statusline and extract info
		my $line='';
		if ($end eq 'sql')
		{
			print "<br>Need to check Filename ".$fname;
			open(DATEI,"<$fpath");
			$line=<DATEI>;			
			close(DATEI);
		}
		if ($end eq 'gz')
		{
			#$gz = gzopen($fpath, "r");
			#$gz->gzreadline($line);
			#$gz->gzclose;
			$line="gz";
			print $line;		
		}
		
		if (length($line)>0)
		{
			#statusline read
			my @infos=split(/\:/,$line);
			my $file_multipart=@infos[3];
			$file_multipart=~ s/\MP_/ /g;
			my $file_databasename=@infos[4];
			print " - <strong>".$file_multipart.' '.$file_databasename.'</strong><br>';
		
		}
	}
}

sub DoAutoDeleteCount {
	my @str;
	my @dbarray;
	my $item;
	my %dbanz;
	my $anz=@filearr;
	@filearr=sort{"$b" gt "$a"}(@filearr);

	print "<br>Filearray: ";
	foreach $key (@filearr) 
	{
	     print "<br>$key: @filearray{$key}";
	}
	print "<hr>";
	
	if($max_backup_files_each==0) {
		PrintOut("Autodelete by count ($max_backup_files) => found $anz Backups");
		if ($anz>0)
		{
			for($i = 0; $i < ($anz-$max_backup_files); $i++) {
				@str=split(/\|/,$filearr[$i]);
				push(@trash_files, $str[2]);
			}
		}
	} else {
		PrintOut("Autodelete by count each DB ($max_backup_files) => found $anz Backups");
		if ($anz>0)
		{
			foreach $item (@filearr) {
				@str=split(/\|/,$item);
				$dbanz{$str[1]}++;
					push(@trash_files, $str[2]) if($dbanz{$str[1]}>$max_backup_files);
			}
		}
	}
}

sub DeleteFiles 
{
	if(@trash_files==0) 
	{
		PrintOut("<font color=red><b>No file to delete.</b></font>");
	}
	else 
	{
		foreach $datei(@trash_files) 
		{
			PrintOut("<font color=red><b>".$datei." deleted.</b></font>");
			write_log( "Perl Cronsript Autodelete - $datei deleted.\n" ) ;
			#unlink($datei);
			unlink("C:/PHP/msd1.23/work/backup/525_2008_02_20_14_42_*.*");
		}
		undef(@trash_files);
	}
}

sub ExecuteCommand {
	my $cmt = shift(@_);
	my (@cad, $errText, $succText, $cd2, $commandDump);
	my $err='';
	
	if($cmt==1) {  #before dump
		$commandDump=$command_beforedump;
		$errText="Error while executing Query before Dump";
		$succText="executing Query before Dump was successful";
	} else {
		$commandDump=$command_afterdump;
		$errText="Error while executing Query after Dump";
		$succText="executing Query after Dump was successful";
	}
	if(length($commandDump)>0) {
		#jetzt ausführen
		if(substr($commandDump,0,7) ne "system:") {
			$dbh = DBI->connect("DBI:mysql:$dbname:$dbhost:$dbport","$dbuser","$dbpass")|| die "Database connection not made: $DBI::errstr"; 
			$dbh->{PrintError} = 0;
			if(index($commandDump,";")>=0) {
				@cad=split(/;/,$commandDump);
				for($i=0;$i<@cad;$i++) {
					if($cad[$i] ne ""){
						
						$sth = $dbh->prepare($cad[$i]);
						$sth->execute or $err=$sth->errstr();
						if ($err ne '') { write_log("Executing Command $cad[$i] caused an error: $err \n"); }
						else { write_log("Executing Command ($cad[$i]) was successful\n"); }
						$sth->finish;
					}
				}
			} else {
				write_log("Executing Command ($commandDump)\n");
				if($commandDump) {
					$sth = $dbh->prepare($commandDump);
					$sth->execute or $err=$sth->errstr();
					if ($err ne '') { write_log("Executing Command ($cad[$i] caused an error: ".$err." \n"); }
					else { write_log("Executing Command ($cad[$i] was successful\n"); }
					$sth->finish;
				}
			}
			if($@){
				my $ger=$@;
				PrintOut("<p style=\"color:red;\">$errText ($commandDump):$ger</p>");
				write_log("$errText ($commandDump): $ger\n");
			} else {
				PrintOut("<p style=\"color:blue;\">$succText</p>");
				write_log("$succText\n");
			}
		} else {
			#Systembefehl
			$commandDump=substr($commandDump,7);
			system($commandDump);
			PrintOut("<p style=\"color:blue;\">$succText ($commandDump)</p>");
			write_log("$succText ($commandDump)\n");
		}
	}
}

sub closeScript {
	my ($Start, $Jetzt, $Totalzeit);
	$Start = $^T; $Jetzt = (time); 
	$Totalzeit=$Jetzt - $Start;
	($Sekunden, $Minuten, $Stunden, $Monatstag, $Monat, $Jahr, $Wochentag, $Jahrestag, $Sommerzeit) = localtime(time);
	$Jahr+=1900;$Monat+=1;$Jahrestag+=1;
	$starttime=sprintf("%02d",$Monatstag).".".sprintf("%02d",$Monat).".".$Jahr."  ".sprintf("%02d",$Stunden).":".sprintf("%02d",$Minuten);
	if($cron_save_all_dbs!=1) {
		PrintOut("closing script <strong>$starttime</strong>");
		PrintOut("<em>total time used: $Totalzeit sec.</em>");
		PrintOut("#EOS (End of script)<hr></body></html>");
		# Datenbankverbindung schliessen
		$sth->finish() if (defined $sth);
		($dbh->disconnect() || warn $dbh->errstr) if (defined $dbh);
	}
}

sub trim 
{ 
        my $string = shift; 
        if (defined($string)) 
        { 
                $string =~ s/^\s+//; 
                $string =~ s/\s+$//; 
        } 
        else 
        { 
                $string=''; 
        } 
        return $string; 
} 

sub byte_output
{
	my $bytes= shift;
	my $suffix="B";
	if ($bytes>=1024) { $suffix="KB"; $bytes=$bytes/1024;} ;
	if ($bytes>=1024) { $suffix="MB"; $bytes=$bytes/1024;};
	my $ret=sprintf "%.2f",$bytes;
	$ret.=' '.$suffix;
	return $ret;
}
