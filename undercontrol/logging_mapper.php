<?php
class LoggingMapper extends DBMapper
{
    public function insertRowToDB($conn, string $table_name, object $new_object)
    {
        $insert_successful = false;

        $sql = "INSERT INTO " . $table_name . " (user_id, logging_type, logging_content, timestamp) VALUES (?, ?, ?, ?)";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Set default timezone to Singapore (GMT +8)
        date_default_timezone_set('Asia/Singapore');

        // Retrieve current datetime to insert the feedback
        $current_datetime = date('Y-m-d H:i:s');

        // Bind and execute
        $sql_prepared_statement->bind_param("isss", $new_object->user_id, $new_object->logging_type, $new_object->logging_content, $current_datetime);

        // Check if insert statement is successful
        if ($sql_prepared_statement->execute()) {
            $insert_successful = true;
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $insert_successful;
    }

    public function readAllRowsFromDB($conn, string $table_name)
    {
        // Not necessary to implement
    }

    public function readRowByIDFromDB($conn, string $table_name, $id)
    {
        // Not necessary to implement
    }

    public function updateRowToDB($conn, string $table_name, $id, object $updated_object)
    {
        // Not necessary to implement
    }

    public function deleteRowFromDB($conn, string $table_name, $id)
    {
        // Not necessary to implement
    }
}
?>