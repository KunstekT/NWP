<?php
    include("zadatak2.php");

    function decryptAllFiles($directory, $cipher, $decryption_key, $decryption_iv,$options){
        $files = scandir($directory);

        $target_dir = "decrypted";
        if (!is_dir($target_dir)) {
            if (!@mkdir($target_dir)) {
                die("<p>Ne možemo stvoriti direktorij '$target_dir'.</p>");
            }
        }        
        for($i = 0; $i < count($files); $i++)
        {
            if(basename($files[$i]) == ".." || basename($files[$i]) == "." ){
                continue;
            }
            // echo "File ($i): ".basename($files[$i]."<br>");

            $encryptedData = file_get_contents("uploads"."/".$files[$i]);
            $decryptedData = openssl_decrypt($encryptedData , $cipher, $decryption_key, 0, $decryption_iv);
            
            file_put_contents($target_dir.'/'.basename($files[$i]), $decryptedData);
        }
    }
    function deleteDecryptedFiles(){
        $directory = "decrypted";
        if (!is_dir($directory)) {
            if (!@mkdir($directory)) {
                die("<p>Ne možemo stvoriti direktorij '$directory'.</p>");
            }
        }     
        $files = scandir($directory);
        for($i = 0; $i < count($files); $i++)
        {
            unlink($files[$i]);
        }
    }

    function showDownloadLinks($target_dir){
        if ($handle = opendir($target_dir."/")) {
            echo "Download links: <br>";
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    echo "<a href='download.php?file=".$entry."'>".$entry."</a><br>";
                }
            }
            closedir($handle);
        }
    }

    $cipher = 'AES-128-CTR';
    $key = md5('jed4n j4k0 v3l1k1 kljuc');
    $iv = $_SESSION['iv'];
    $options = OPENSSL_RAW_DATA;
    // echo "IV: ".$iv."<BR>";

    decryptAllFiles("uploads", $cipher, $key, $iv, $options); 
    showDownloadLinks("decrypted");
    // deleteDecryptedFiles();

?>