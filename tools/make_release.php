<?php

$config = require __DIR__.'/config.php';

date_default_timezone_set('Asia/Jakarta');

echo PHP_EOL;
echo "========================================".PHP_EOL;
echo " POS RELEASE TOOL".PHP_EOL;
echo "========================================".PHP_EOL.PHP_EOL;

/*
|--------------------------------------------------------------------------
| Cari backup terbaru
|--------------------------------------------------------------------------
*/

$files = glob($config['backup_path'].'/*.sql');

if(empty($files))
{
    exit("Tidak ada file backup.\n");
}

usort($files,function($a,$b){

    return filemtime($b)-filemtime($a);

});

$backupFile = $files[0];

echo "Backup : ".basename($backupFile).PHP_EOL.PHP_EOL;

/*
|--------------------------------------------------------------------------
| Folder release
|--------------------------------------------------------------------------
*/

if(!is_dir($config['release_path']))
{
    mkdir($config['release_path'],0777,true);
}

$date=date('Y-m-d_H-i-s');

$releaseFile=
$config['release_path'].
DIRECTORY_SEPARATOR.
"release_{$date}.sql";

/*
|--------------------------------------------------------------------------
| Streaming
|--------------------------------------------------------------------------
*/

$in=fopen($backupFile,'r');

$out=fopen($releaseFile,'w');

if(!$in || !$out)
{
    exit("Gagal membuka file.\n");
}

$skip=false;

$currentTable='';

$removed=[];

foreach($config['transaction_tables'] as $t)
{
    $removed[$t]=0;
}

while(($line=fgets($in))!==false)
{

    /*
    |--------------------------------------------------------------------------
    | Sedang skip INSERT
    |--------------------------------------------------------------------------
    */

    if($skip)
    {

        if(str_contains(trim($line),';'))
        {
            $skip=false;
        }

        continue;

    }

    /*
    |--------------------------------------------------------------------------
    | Deteksi INSERT INTO
    |--------------------------------------------------------------------------
    */

    if(preg_match('/^INSERT INTO `(.*?)`/',$line,$m))
    {

        $table=$m[1];

        if(in_array($table,$config['transaction_tables']))
        {

            $skip=true;

            $currentTable=$table;

            $removed[$table]++;

            if(str_contains(trim($line),';'))
            {
                $skip=false;
            }

            continue;

        }

    }

    fwrite($out,$line);

}

fclose($in);

fclose($out);

echo "Tabel dibersihkan".PHP_EOL.PHP_EOL;

foreach($removed as $table=>$count)
{

    if($count>0)
    {
        echo "✓ ".$table.PHP_EOL;
    }

}

echo PHP_EOL;

echo "Release :".PHP_EOL;

echo basename($releaseFile).PHP_EOL.PHP_EOL;

echo "Ukuran : ".
round(filesize($releaseFile)/1024/1024,2)
." MB".PHP_EOL.PHP_EOL;

echo "SELESAI".PHP_EOL;