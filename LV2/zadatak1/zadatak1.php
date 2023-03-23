<?php
    function get_column_names($dbc, $table) {
        $sql = 'DESCRIBE '.$table;
        $result = mysqli_query($dbc, $sql);

        $rows = array();
        while($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row['Field'];
        }

        return $rows;
    }
   
    function backupDatabaseTables($dbConnectionData){

        $db_name = $dbConnectionData["dbname"];
        $dir = "backup";
        $time = time();

        $dbc = @mysqli_connect(
            $dbConnectionData["servername"], 
            $dbConnectionData["username"], 
            $dbConnectionData["password"], 
            $dbConnectionData["dbname"]
        ) OR die("<p>Ne možemo se spojiti na bazu $db_name.</p></body></html>");

        $r = mysqli_query($dbc, 'SHOW TABLES'); 

        // prekidamo backup ako ne postoji niti jedna tablica
        if(mysqli_num_rows($r) <= 0) 
        {
            echo "<p>Baza $db_name ne sadrži tablice.</p>";
            return;
        }

        echo "<p>Backup za bazu podataka '$db_name'.</p>";        

        while (list($table) = mysqli_fetch_array($r,MYSQLI_NUM)) {
            // array_push($tables, );
            $q = "SELECT * FROM $table";
            $r2 = mysqli_query($dbc, $q);

            //Ako ne postoje podaci, break
            if (mysqli_num_rows($r2) <= 0) {
                //Ne možemo stvoriti datoteku
                echo "<p>Datoteka $dir/{$table}_{$time}.sql.gz se ne može otvoriti.</p>";
                break; //Prekini while petlju
            }

            $colNamesTXT ="";
            $rowValues = "";

            $col_names = get_column_names($dbc, $table);
            for($i = 0; $i<count($col_names); $i++){
                $colNamesTXT = $colNamesTXT."'".$col_names[$i]."'";
                if($i + 1 < count($col_names)){
                    $colNamesTXT = $colNamesTXT.", ";
                }
            }

            if (!is_dir($dir)) {
                if (!@mkdir($dir)) {
                    die("<p>Ne možemo stvoriti direktorij 'backup'.</p></body></html>");
                }
            }

            //Otvori datoteku
            if ($fp = gzopen ("$dir/{$table}_{$time}.sql.gz", 'w9')) {  

                //Dohvat podataka iz tablice
                while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                    $rowValues = "";
                    for($i = 0; $i < count($col_names); $i++){
                        $rowValues = $rowValues."'".$row[$i]."'";
                        if($i + 1 < count($col_names)){
                            $rowValues = $rowValues.", ";
                        }
                    }
                    $txt = "";
                    $txt = $txt."INSERT INTO ".$table." (".$colNamesTXT.")\n";
                    $txt = $txt."VALUES (".$rowValues.");\n";
                    // echo "<br>".$txt."<br>"; 
                    gzwrite ($fp, $txt);
                }
                gzclose ($fp);
                echo "<p>Tablica '$table' je pohranjena.</p>";
            }
        }
        $dbc->close();
    }

    $dbConnectionData = array(
        'servername' => "localhost",
        'username' => "root",
        'password' => "",
        'dbname' => "radovi"
        );

    backupDatabaseTables($dbConnectionData);
?>
