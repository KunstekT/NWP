<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
  Select file to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload" name="submit">
</form>

</body>
</html>

<?php
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function encrypt($data, $cipher, $encryption_key, $options){    
        $iv_length = openssl_cipher_iv_length($cipher);        
        $encryption_iv = random_bytes($iv_length); 
        // echo "<br>encryption_iv: ".$encryption_iv."<br>";
        $data = openssl_encrypt($data , $cipher, $encryption_key, $options , $encryption_iv );
        $_SESSION['podaci'] = base64_encode($data);
        $_SESSION['iv'] = $encryption_iv;

        return $_SESSION['podaci'];

    }
    function decrypt($data, $cipher, $decryption_key, $options){

        if (isset($_SESSION['podaci'], $_SESSION['iv'])) {
            $decryption_iv = $_SESSION['iv'];
            // $data= base64_decode( $_SESSION['iv'] );
            $data = openssl_decrypt($data , $cipher, $decryption_key, OPENSSL_RAW_DATA , $decryption_iv );
            echo '<p>Dekriptirani podaci su "' . $data . '".</p>';
        } else {
            echo '<p>Nema podataka.</p>';
        }
    }

    session_start();
?>

