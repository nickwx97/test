<?php
abstract class DBMapper
{
    // Universal function to connect to database from config file
    public function readFile()
    {
        // Actual connect to VM Textfile
        $myfile = parse_ini_file("/home/team-10/holder/info.ini");

        //$myfile = parse_ini_file("config.ini");

        $servername = $myfile["server"];
        $user = $myfile["username"];
        $password = $myfile["password"];
        $db = $myfile["database_name"];
        $conn = new mysqli($servername, $user, $password, $db);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    //Actual DB-related methods via the child Mapper class created by the Factory
    abstract function insertRowToDB($conn, string $table_name, object $new_object);
    abstract function readAllRowsFromDB($conn, string $table_name);
    abstract function readRowByIDFromDB($conn, string $table_name, $id);
    abstract function updateRowToDB($conn, string $table_name, $id, object $updated_object);
    abstract function deleteRowFromDB($conn, string $table_name, $id);

}
?>
