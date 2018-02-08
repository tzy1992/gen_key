<?php
$filename = round(microtime(true) * 1000).'_';
$cmd_1 = 'cd /d C:\xampp\htdocs\gen_key\openssl\bin' ;
$cmd_2 = 'openssl  genrsa  -out key.pem 1024';
$cmd_3 = 'openssl  rsa -in key.pem  -pubout  -out  merchant_public_key.txt';
$cmd_4 = 'openssl pkcs8 -topk8 -inform PEM -in key.pem -out merchant_private_key.txt -nocrypt';
$cmd_5 = 'move C:\xampp\htdocs\gen_key\openssl\bin\merchant_public_key.txt  C:\xampp\htdocs\gen_key\merchant_public_key.txt';
$cmd_6 = 'move C:\xampp\htdocs\gen_key\openssl\bin\merchant_private_key.txt C:\xampp\htdocs\gen_key\merchant_private_key.txt';
$cmd_7 = 'del C:\xampp\htdocs\gen_key\*.txt';
//$cmd_8 = 'del C:\xampp\htdocs\gen_key\openssl\bin\key.pem';
$key_status = "";


exec($cmd_1 ."&& ".$cmd_2."&& ".$cmd_3."&& ".$cmd_4."&& ".$cmd_5."&& ".$cmd_6, $output, $return_var);

if(!$return_var){
    echo "RSA Key Generated";
    $key_status = "1";
}
else{
    echo "Generate Fail ";
    $key_status = "";
}

 $files = array('merchant_public_key.txt','merchant_private_key.txt');

    # create new zip opbject
    $zip = new ZipArchive();

    # create a temp file & open it
    $tmp_file = tempnam('.','');
    $zip->open($tmp_file, ZipArchive::CREATE);

    # loop through each file
    foreach($files as $file){

        # download file
        $download_file = file_get_contents($file);

        #add it to the zip
        $zip->addFromString(basename($filename.$file),$download_file);

    }

    # close zip
    $zip->close();

    # send the file to the browser as a download
    if($key_status != NULL){
    header('Content-disposition: attachment; filename=Merchant_Key_Pairs.zip');
    header('Content-type: application/zip');
    readfile($tmp_file);
    unlink($tmp_file);
    exec($cmd_7 ."&& ".$cmd_8);

   	}
	else{
		echo '<br>'.'download fail';
		unlink($tmp_file);
	}





?>