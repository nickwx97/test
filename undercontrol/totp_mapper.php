<?php
// Mapper class to store all SQL queries pertaining to TOTP Table
class TOTPMapper extends DBMapper
{
    public function insertRowToDB($conn, string $table_name, object $new_object)
    {
        $insert_successful = false;

        $sql = "INSERT INTO " . $table_name . " (account_id, otp_expiry_date, otp) VALUES (?, ?, ?)";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("iss", $new_object->account_id, $new_object->expiry_date, $new_object->otp);

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
        $sql = "SELECT * FROM " . $table_name . " WHERE totp_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $id);
        $sql_prepared_statement->execute();

        // Get the mysqli result
        $result =  $sql_prepared_statement->get_result();

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $result;
    }

    public function readOTPByAccountIDFromDB($conn, string $table_name, $account_id)
    {
        $sql = "SELECT otp FROM " . $table_name . " WHERE account_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $account_id);
        $sql_prepared_statement->execute();

        // Get the mysqli result
        $result =  $sql_prepared_statement->get_result();

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $otp = $row["otp"];
            }
        } else {
            $otp = "0 results";
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $otp;
    }

    public function readExpiryDateByAccountIDFromDB($conn, string $table_name, $account_id)
    {
        $sql = "SELECT expiry_date FROM " . $table_name . " WHERE account_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $account_id);
        $sql_prepared_statement->execute();

        // Get the mysqli result
        $result =  $sql_prepared_statement->get_result();

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $expiry_date = $row["expiry_date"];
            }
        } else {
            $expiry_date = "0 results";
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $expiry_date;
    }

    public function updateRowToDB($conn, string $table_name, $id, object $updated_object)
    {
        $update_successful = false;

        $sql = "UPDATE " . $table_name . " SET expiry_date=? WHERE login_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("si", $updated_object->expiry_date, $id);

        // Check if update statement is successful
        if ($sql_prepared_statement->execute()) {
            $update_successful = true;
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $update_successful;
    }

    public function deleteRowFromDB($conn, string $table_name, $id)
    {
        $delete_successful = false;

        $sql = "DELETE FROM " . $table_name . " WHERE account_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $id);

        // Check if delete statement is successful
        if ($sql_prepared_statement->execute()) {
            $delete_successful = true;
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $delete_successful;
    }
}