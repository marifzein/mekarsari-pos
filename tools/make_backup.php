<?php

$config = require __DIR__.'/config.php';
date_default_timezone_set('Asia/Jakarta');

echo PHP_EOL;
echo "========================================".PHP_EOL;
echo " POS BACKUP TOOL".PHP_EOL;
echo "========================================".PHP_EOL.PHP_EOL;

// cek mysqldump
if(!file_exists($config['mysqldump']))
{
    exit("ERROR : mysqldump.exe tidak ditemukan\n\n".$config['mysqldump'].PHP_EOL);
}

// buat folder backup
if(!is_dir($config['backup_path']))
{
    mkdir($config['backup_path'],0777,true);
}

// nama file
$date=date('Y-m-d_H-i-s');

$backupFile=$config['backup_path'].
DIRECTORY_SEPARATOR.
'backup_'.$date.'.sql';

// command
$command='"'.$config['mysqldump'].'" ';

$command.='-h '.$config['host'].' ';

$command.='-P '.$config['port'].' ';

$command.='-u '.$config['username'].' ';

if($config['password']!='')
{
    $command.='-p'.$config['password'].' ';
}

$command.=$config['database'].' > "'.$backupFile.'"';

exec($command,$output,$result);

// hasil
if($result!==0)
{
    exit("Backup GAGAL".PHP_EOL);
}

echo "Database : ".$config['database'].PHP_EOL.PHP_EOL;

echo "Backup :".PHP_EOL;

echo $backupFile.PHP_EOL.PHP_EOL;

echo "Ukuran : ".
round(filesize($backupFile)/1024/1024,2)
." MB".PHP_EOL.PHP_EOL;

echo "SELESAI".PHP_EOL;