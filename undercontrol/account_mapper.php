<?php
require_once('logging.php');
// Mapper class to store all SQL queries pertaining to Account Table
class AccountMapper extends DBMapper
{
    public function insertRowToDB($conn, string $table_name, object $new_object)
    {
        $insert_successful = false;

        $sql = "INSERT INTO " . $table_name . " (account_fullname, account_username, account_email, user_privilege) VALUES (?, ?, ?, ?)";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("ssss", $new_object->fullname, $new_object->username, $new_object->email, $new_object->user_privilege);

        // Check if insert statement is successful
        if ($sql_prepared_statement->execute()) {
            $insert_successful = true;
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $insert_successful;
    }

    public function readAllFireAwayStaffFromDB($conn, string $table_name, string $account_id)
    {
        $sql = "SELECT * FROM " . $table_name . " WHERE user_privilege NOT LIKE ? AND account_id NOT IN (?)";

        // Privilege level = 3 means administrator
        $administrator = '%' . '3' . '%';

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("si", $administrator, $account_id);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();
        return $result;
    }

    public function readAllRowsFromDB($conn, string $table_name)
    {
        $sql = "SELECT * FROM " . $table_name;
        $result = $conn->query($sql);
        return $result;
    }

    public function readLatestAccountByIDFromDB($conn, string $table_name)
    {
        $sql = "SELECT * FROM " . $table_name . " ORDER BY account_id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $id = $row["account_id"];
            }
        } else {
            echo "0 results";
        }
        return $id;
    }

    public function readRowByIDFromDB($conn, string $table_name, $id)
    {
        $sql = "SELECT * FROM " . $table_name . " WHERE account_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $id);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        $final_result = mysqli_fetch_assoc($result);

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $final_result;
    }

    public function updateRowToDB($conn, string $table_name, $id, object $updated_object)
    {
        $update_successful = false;

        $sql = "UPDATE " . $table_name . " SET account_fullname=?, account_username=?, account_email=?, user_privilege=? WHERE account_id=?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("sssss", $updated_object->fullname, $updated_object->username, $updated_object->email, $updated_object->user_privilege, $id);

        // Check if update statement is successful
        if ($sql_prepared_statement->execute()) {
            $update_successful = true;
        }
        
        // Log for updating account
        if ($update_successful){
            logEvent($_SESSION['user_id'], "Success", "Updated account");
        }else {
            logEvent($_SESSION['user_id'], "Failure", "Fail to update account");
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

    public function readIDFromDBbyUsername($conn, string $table_name, $username)
    {
        $sql = "SELECT account_id FROM " . $table_name . " WHERE account_email=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("s", $username);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $id = $row["account_id"];
            }
        } else {
            $id= "0 results";
        }

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $id;
    }

    public function verifyUpdateAccDuplication($conn, string $table_name, $session_id, $username, $email) {
        $no_duplication = false;
        $sql = "SELECT account_username, account_email FROM " . $table_name . " WHERE account_id NOT IN (?) AND (account_username=? OR account_email =?)";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("sss", $session_id,$username,$email);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $no_duplication = false;
            }
        } else {
            $no_duplication = true;
        }
        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();
        return $no_duplication;
    }

    public function verifyCreateAccDuplication($conn, string $table_name, $username, $email) {
        $no_duplication = false;
        $sql = "SELECT * FROM " . $table_name . " WHERE account_username=? OR account_email =?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("ss", $username, $email);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();
        if ($result->num_rows > 0) {
            $no_duplication = false;
        } else {
            $no_duplication = true;
        }
        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();
        return $no_duplication;
    }

    public function getIDFromDBbyEmail($conn, string $table_name, $email)
    {
        $sql = "SELECT account_id FROM " . $table_name . " WHERE account_email=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("s", $email);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $result;
    }

    public function getEmailFromDBbyID($conn, string $table_name, $ID)
    {
        $sql = "SELECT account_email FROM " . $table_name . " WHERE account_id=?";
        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("s", $ID);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();

        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();

        return $result;
    }

    public function verifyEmailExists($conn, string $table_name, $email) {
        $verifyExists = false;
        $sql = "SELECT * FROM " . $table_name . " WHERE account_email =?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("s", $email);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();
        if ($result->num_rows > 0) {
            $verifyExists = true;
        } else {
            $verifyExists = false;
        }
        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();
        return $verifyExists;
    }

    public function checkAccountExistance($conn, string $table_name, $account_id) {
        $verifyExists = false;
        $sql = "SELECT * FROM " . $table_name . " WHERE account_id =?";

        // Using SQL prepared statements to minimise SQL Injections
        $sql_prepared_statement = $conn->prepare($sql);

        // Bind and execute
        $sql_prepared_statement->bind_param("i", $account_id);
        $sql_prepared_statement->execute();

        // Retrieve the mysqli result
        $result =  $sql_prepared_statement->get_result();
        if ($result->num_rows > 0) {
            $verifyExists = true;
        } else {
            $verifyExists = false;
        }
        // Close all forms of database connection instances
        $sql_prepared_statement->close();
        $conn->close();
        return $verifyExists;
    }
}
?>
