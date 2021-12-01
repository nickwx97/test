<?php
require_once "logging.php";
// Mapper class to store all SQL queries pertaining to Login Table
class LoginMapper extends DBMapper
{
    public function insertRowToDB($conn, string $table_name, object $new_object)
    {
        $insert_successful = false;

        $sql = "INSERT INTO " . $table_name . " (account_id, password, secret_key) VALUES (?, ?, ?)";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("iss", $new_object->account_id, $new_object->passwordVerifier, $new_object->smallSalt);

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
        // Not implemented due to security reasons
    }

    public function readRowByIDFromDB($conn, string $table_name, $id)
    {
        // Not implemented due to security reasons
    }

    public function updateRowToDB($conn, string $table_name, $id, object $updated_object)
    {
        $update_successful = false;

        $sql = "UPDATE " . $table_name . " SET password=?, secret_key=? WHERE login_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("ssi", $updated_object->passwordVerifier, $updated_object->smallSalt, $id);

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


    public function readpassFromDBbyID($conn, string $table_name, $ID)
    {
        $sql = "SELECT PASSWORD FROM " . $table_name . " WHERE account_id=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $ID);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $password = $row["PASSWORD"];
            }
        } else {
            $password = "0 results";
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $password;
    }
    

    public function readSecretKeyFromDBbyID($conn, string $table_name, $ID)
    {
        $sql = "SELECT secret_key FROM " . $table_name . " WHERE account_id=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("s", $ID);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $password = $row["secret_key"];
            }
        } else {
            $password = "0 results";
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $password;
    }

    public function readGenerateXFromDBbyID($conn, string $table_name, $ID)
    {
        $sql = "SELECT generateX FROM " . $table_name . " WHERE account_id=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $ID);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $password = $row["generateX"];
            }
        } else {
            $password = "0 results";
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $password;
    }

    public function updateTokenCode($conn, string $table_name, $accountID, $token) {
        $update_successful = false;
        
        $sql = "UPDATE " . $table_name . " SET token=? WHERE account_id=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("ss", $token, $accountID);
        $sql_prepared_statement->execute();

        if ($sql_prepared_statement->execute()) {
            $update_successful = true;
        }
        
        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();
        return $update_successful;
    }

    public function updatePassword($conn, string $table_name, $passVerify, $smallSalt, $accID) {
        $updatePassSuccess = false;

        $sql = "UPDATE " . $table_name . " SET password=?, secret_key=? WHERE account_id=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("ssi", $passVerify, $smallSalt, $accID);
        $sql_prepared_statement->execute();

        // Check if update statement is successful
        if ($sql_prepared_statement->execute()) {
            $updatePassSuccess = true;
        }
        
        // Log for update password success
        if ($updatePassSuccess){
            logEvent($accID, "Success", "Attempt password change");
        }else {
            logEvent($accID, "Failure", "Fail to change password");
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $updatePassSuccess;
    }
}
?>
