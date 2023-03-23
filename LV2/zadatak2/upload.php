    
<?php
    include("zadatak2.php");

    function encryptUploadedFile($target_file, $target_dir, $cipher, $key, $options){
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded."; 

            $randomFileName = generateRandomString();
            //TODO: check if randomFileName already exists in server folder (very rare case); if true: choose another

            $createfile = fopen($target_dir.'/'.$randomFileName.".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION)), 'wb');
            
            $fp = fopen($target_file, 'rb');
            $data = fread($fp, 500000);
            fclose($fp);

            $encryptedFile = encrypt($data, $cipher, $key, $options);
            fwrite($createfile,$encryptedFile);
            fclose($createfile);

            unlink($target_file);      
        } 
        else {
            echo "There was an error uploading your file.";
        }
    }

    function UploadFile(){
        $target_dir = "uploads";
        if (!is_dir($target_dir)) {
            if (!@mkdir($target_dir)) {
                die("<p>Ne možemo stvoriti direktorij '$target_dir'.</p></body></html>");
            }
        }
        $target_file = $target_dir."/".basename($_FILES["fileToUpload"]["name"]);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));   
        

        if(isset($_POST["submit"]) && !empty($_FILES["fileToUpload"]["name"])){
            if($fileType != "pdf" && $fileType != "png" && $fileType != "jpeg" && $fileType != "jpg") {
                echo "File format not allowed."; 
                echo "Your file was not uploaded."; 
                return;
            }
            $cipher = 'AES-128-CTR';
            $key = md5('jed4n j4k0 v3l1k1 kljuc');
            $options = OPENSSL_RAW_DATA;

            encryptUploadedFile($target_file, $target_dir, $cipher, $key, $options);
        }
        else echo "No file chosen.";    
        
    }

    UploadFile();
?>