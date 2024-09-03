<?php
include "config.php";

$hostname = "hostname";
$username = "username";
$password = "password";
$dbname = "dbname";

$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

$tables = array();
$result = $pdo->query("SHOW TABLES");
while ($row = $result->fetch()) {
    $tables[] = $row[0];
}

$return = "";
foreach ($tables as $table) {
    $result = $pdo->query("SELECT * FROM $table");
    $num_fields = $result->columnCount();

    $return .= "DROP TABLE $table;";
    $row2 = $pdo->query("SHOW CREATE TABLE $table")->fetch();
    $return .= "\n\n" . $row2[1] . ";\n\n";

    for ($i = 0; $i < $num_fields; $i++) {
        while ($row = $result->fetch()) {
            $return .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = str_replace("\n", "\\n", $row[$j]);
                if (isset($row[$j])) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ($j < ($num_fields - 1)) {
                    $return .= ',';
                }
            }
            $return .= ");\n";
        }
    }
    $return .= "\n\n\n";
}

// save file
$handle = fopen('db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');
fwrite($handle, $return);
fclose($handle);








echo "Completed";
?> 