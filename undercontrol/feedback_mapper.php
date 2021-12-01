<?php
// Mapper class to store all SQL queries pertaining to Feedback Table
class FeedbackMapper extends DBMapper
{
    public function insertRowToDB($conn, string $table_name, object $new_object)
    {
        $insert_successful = false;

        $sql = "INSERT INTO " . $table_name . " (feedback_subject, fullname, country_code, mobile_no, email, feedback_type, feedback_content, feedback_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Set default timezone to Singapore (GMT +8)
        date_default_timezone_set('Asia/Singapore');

        // Retrieve current datetime to insert the feedback
        $current_datetime = date('Y-m-d H:i:s');

        // Bind and execute
        $sql_prepared_statement->bind_param("sssissss", $new_object->feedback_subject, $new_object->public_fullname, $new_object->public_country_code, $new_object->public_mobile_no, $new_object->public_email, $new_object->feedback_type, $new_object->feedback_content, $current_datetime);

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
        $sql = "SELECT * FROM " . $table_name;
        $result = $conn->query($sql);
        return $result;
    }

    public function readRowByIDFromDB($conn, string $table_name, $id)
    {
        // Not necessary to implement
    }

    public function updateRowToDB($conn, string $table_name, $id, object $updated_object)
    {
        // No update feedback information implementation required
    }

    public function deleteRowFromDB($conn, string $table_name, $id)
    {
        $delete_successful = false;

        $sql = "DELETE FROM " . $table_name . " WHERE feedback_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $id);

        // Check if insert statement is successful
        if ($sql_prepared_statement->execute()) {
            $delete_successful = true;
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $delete_successful;
    }
    
    public function readCountLimitFromDB($conn, string $table_name, $email, $date)
    {
        $likeDate = '%' . $date . '%';
        $sql = "SELECT count(*) as total FROM " . $table_name . " WHERE email = ? and feedback_datetime LIKE ?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("ss", $email, $likeDate);
        $sql_prepared_statement->execute();
        $result = $sql_prepared_statement->get_result();
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $count = $row['total'];
            }
        } else {
            $count = 0;
        }
        $sql_prepared_statement->close();
        $conn->close();
        return $count;
    }
}
?>