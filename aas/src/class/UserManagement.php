<?php
session_start();
include_once "database.php";
include_once "./sendEmails.php";


class UserManagement
{
    public $userTable;
    public $dbObj;
    public $con;

    public function __construct()
    {
        $this->userTable = "users";
        $this->dbObj = new Database();
        $this->con = $this->dbObj->connection();
    }

    // register account
    public function registration()
    {
        $username = $this->con->real_escape_string($_POST['username']);
        $contact_no = $this->con->real_escape_string($_POST['contact_no']);
        $email = $this->con->real_escape_string($_POST['email']);
        $first_name = $this->con->real_escape_string($_POST['first_name']);
        $last_name = $this->con->real_escape_string($_POST['last_name']);
        $password = $this->con->real_escape_string($_POST['password']);
        $dob = $this->con->real_escape_string($_POST['dob']);
        $profile_pic = $this->con->real_escape_string($_POST['profile_pic']);
        $EncryptPassword = password_hash($password, PASSWORD_DEFAULT);

        $filename = $_FILES["profile_pic"]["name"];
        $tempname = $_FILES["profile_pic"]["tmp_name"];
        $folder = "images/" . $filename;
        
        // generate unique token
        $token = bin2hex(random_bytes(50)); 

        $sql = $this->con->prepare("INSERT INTO users (email, first_name, last_name, password, dob,contact_no, profile_pic, token, username) VALUES(?,?,?,?,?,?,?,?,?)");
        $sql->bind_param("sssssisss", $email, $first_name, $last_name, $EncryptPassword, $dob, $contact_no, $filename, $token, $username);
        
        if (move_uploaded_file($tempname, $folder)) {
            $msg = "Image uploaded successfully";
        } else {
            $msg = "Failed to upload image";
        }

        $sql->execute();
        sendVerificationEmail($email,$token);
        $result = $sql->get_result();
         
        if ($sql == true) {
            return true;
        } else {
            return false;
        }
        $sql->close();
    }

    public function verifyCredentials(){
        $email = $this->con->real_escape_string($_POST['email']);
        $password = $this->con->real_escape_string($_POST['password']);

        //check if employee or not
        //run guest sql method
        //check which user
        $sql = $this->con->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        while ($student_data = $result->fetch_assoc()) {
            if(!password_verify($password, $student_data['password'])){
                return false;
            }
            $_SESSION['temp_user_id'] = $student_data['user_id'];
            $_SESSION['temp_username'] = $student_data['username'];
        }

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Guest and Admin login
    public function login()
    {
        $email = $this->con->real_escape_string($_SESSION['email']);
        $password = $this->con->real_escape_string($_SESSION['password']);
        // $password = password_verify($password);

        unset($_SESSION["temp_user_id"]);
        unset($_SESSION["temp_username"]);
        unset($_SESSION["email"]);
        unset($_SESSION["password"]);

        //check if employee or not
        //run guest sql method
        //check which user
        $sql = $this->con->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        while ($student_data = $result->fetch_assoc()) {

            if(!password_verify($password, $student_data['password'])){
                return false;
            }

            $_SESSION['user_id'] = $student_data['user_id'];
            $_SESSION['username'] = $student_data['username'];
            $_SESSION['role'] = $student_data['role'];
            $_SESSION['email'] = $student_data['email'];
            $_SESSION['verified'] = $student_data['verified'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['remote_ip'] = $_SERVER['REMOTE_ADDR'];
        }

        //check role 
        //if sessionrole = employee then i run the code to get employee 
        if ($_SESSION['role'] == 'employee') {
            $sql = $this->con->prepare("SELECT * FROM employee e, users u WHERE e.user_id = u.user_id AND u.email = ?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();

            while ($student_data = $result->fetch_assoc()) {
                $_SESSION['user_id'] = $student_data['user_id'];
                $_SESSION['username'] = $student_data['username'];
                $_SESSION['role'] = $student_data['role'];
                $_SESSION['email'] = $student_data['email'];
                $_SESSION['verified'] = $student_data['verified'];
                $_SESSION['employee_id'] = $student_data['employee_id'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['remote_ip'] = $_SERVER['REMOTE_ADDR'];
                session_regenerate_id(true);
            }
                        
            if ($result->num_rows > 0) {
                return true;
            } else {    
                return false;
            }
        }   
        //else guest or admin
        else {  
            $sql = $this->con->prepare("SELECT * FROM users WHERE email = ?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();

            while ($student_data = $result->fetch_assoc()) {
                $_SESSION['user_id'] = $student_data['user_id'];
                $_SESSION['username'] = $student_data['username'];
                $_SESSION['role'] = $student_data['role'];
                $_SESSION['email'] = $student_data['email'];
                $_SESSION['verified'] = $student_data['verified'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['remote_ip'] = $_SERVER['REMOTE_ADDR'];
                session_regenerate_id(true);
            }

            if ($result->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
            $result->close();
}

    // check if email exists
    public function isUserExist($email)
    {
        $sql = $this->con->prepare("SELECT * FROM users WHERE email =?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    // check if username exists
    public function isUsernameExists($username)
    {
        $sql = $this->con->prepare("SELECT * FROM users WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function generateRandomString($length = 15)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // 4got pwd
    public function checkforgetpassword($postData)
    {
        $email = $this->con->real_escape_string($_POST['email']);

        $length = 15;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $code = $randomString;

        $to = $email;
        $subject = "Your Recovered Password";
        $message = "Please use this password to login " . $code;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: Team AAS <teamaas@mail.com>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $mml = mail($to, $subject, $message, $headers);

        if ($mml) {
            $sql = $this->con->prepare("UPDATE users SET password=? where email=?");
            $sql->bind_param("ss", password_hash($code, PASSWORD_DEFAULT), $_POST['email']);
            $sql->execute();
            $result = $sql->get_result();

            if ($sql == true) {
                return true;
            } else {
                return false;
            }
            $sql->close();
        }
    }
    ///    ****************************
    ///    ****************************
    ///    **** CRUD for Employee***
    ///    ****************************
    ///    ****************************

    public function updateProfileRecord($postData)
    {
        $contact_no = $this->con->real_escape_string($_POST['contact_no']);
        $first_name = $this->con->real_escape_string($_POST['first_name']);
        $last_name = $this->con->real_escape_string($_POST['last_name']);
        $email = $this->con->real_escape_string($_POST['email']);
        $dob = $this->con->real_escape_string($_POST['dob']);
        $user_id = $this->con->real_escape_string($_POST['user_id']);

        $filename = $_FILES["profile_pic"]["name"];
        $tempname = $_FILES["profile_pic"]["tmp_name"];
        $folder = "images/" . $filename;

        // !empty($filename)
        if (!empty($user_id) && !empty($postData)) {
            if(!empty($filename)) {
                    $sql = $this->con->prepare("UPDATE users SET email=?, first_name=?, last_name=?, dob=?, contact_no=?, profile_pic=? WHERE user_id=?");
                    $sql->bind_param("ssssisi", $email, $first_name, $last_name,  $dob, $contact_no, $filename, $user_id);
                    if (move_uploaded_file($tempname, $folder)) {
                        //echo "Image uploaded successfully";
                    } else {
                        //echo "Failed to upload image";
                    }
                    $sql->execute();
                    $result = $sql->get_result();
            }
        else // no image uploaded
        {
            $sql = $this->con->prepare("UPDATE users SET email=?, first_name=?, last_name=?, dob=?, contact_no=? WHERE user_id=?");
            $sql->bind_param("ssssii", $email, $first_name, $last_name,$dob, $contact_no, $user_id);
            $sql->execute();
            $result = $sql->get_result();     
        }

        updateProfileEmailNotification($email);
             
        if ($sql == true) {
            return true;
        } else {
            return false;
        }
        $sql->close();
    }
}

    public function updateEmployeeProfileRecord($postData)
    {
        $contact_no = $this->con->real_escape_string($_POST['contact_no']);
        $first_name = $this->con->real_escape_string($_POST['first_name']);
        $last_name = $this->con->real_escape_string($_POST['last_name']);
        $email = $this->con->real_escape_string($_POST['email']);
        $dob = $this->con->real_escape_string($_POST['dob']);
        $user_id = $this->con->real_escape_string($_POST['user_id']);
        $job_title = $this->con->real_escape_string($_POST['job_title']);
        $years_exp = $this->con->real_escape_string($_POST['years_exp']);
        $specialisation = $this->con->real_escape_string($_POST['specialisation']);
        $linkedin = $this->con->real_escape_string($_POST['linkedin']);
        $employee_id = $this->con->real_escape_string($_POST['employee_id']);

        $filename = $_FILES["profile_pic"]["name"];
        $tempname = $_FILES["profile_pic"]["tmp_name"];
        $folder = "images/" . $filename;

        if (!empty($user_id) && !empty($postData)) {
            if(!empty($filename)) {
                $sql = $this->con->prepare("UPDATE users SET email=?, first_name=?, last_name=?, dob=?, contact_no=?, profile_pic=? WHERE user_id=?");
                $sql->bind_param("ssssisi", $email, $first_name, $last_name, $dob, $contact_no, $filename, $user_id);

                if (move_uploaded_file($tempname, $folder)) {
                    //echo "Image uploaded successfully";
                } else {
                    //echo  "Failed to upload image";
                }
                $sql->execute();
                $result = $sql->get_result();

                $sql = $this->con->prepare("UPDATE employee SET job_title=?, years_exp=?, specialisation=?, linkedin=? WHERE employee_id=?");
                $sql->bind_param("sissi", $job_title, $years_exp, $specialisation, $linkedin, $employee_id);
                $sql->execute();
                $result = $sql->get_result();
                $sql->close();
            }
        // no image uploaded
         else{
                $sql = $this->con->prepare("UPDATE users SET email=?, first_name=?, last_name=?, dob=?, contact_no=? WHERE user_id=?");
                $sql->bind_param("ssssii", $email, $first_name, $last_name, $dob, $contact_no, $user_id);
                $sql->execute();
                $result = $sql->get_result();

                $sql = $this->con->prepare("UPDATE employee SET job_title=?, years_exp=?, specialisation=?, linkedin=? WHERE employee_id=?");
                $sql->bind_param("sissi", $job_title, $years_exp, $specialisation, $linkedin, $employee_id);
                $sql->execute();
                $result = $sql->get_result();
                $sql->close();

         }
            updateProfileEmailNotification($email);
            
            if ($sql == true) {
                return true;
            } else {
                return false;
            }
            $sql->close();
        }
    }

     public function updatePassword($postData)
    {
        $user_id = $this->con->real_escape_string($_POST['user_id']);
        $password = $this->con->real_escape_string($_POST['password']);
        $EncryptPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($user_id) && !empty($postData)) {
            $sql = $this->con->prepare("UPDATE users SET password=? WHERE user_id=?");
            $sql->bind_param("si", $EncryptPassword, $user_id);
            $sql->execute();
            $result = $sql->get_result();
            $sql->close();

            if ($sql == true) {
                return true;
            } else {
                return false;
            }
            $sql->close();
        }
    }

    ///    ****************************
    ///    ****************************
    ///    **** CRUD for Admin ***
    ///    ****************************
    ///    ****************************

    // create employee account
    public function insertAccount()
    {
        $username = $this->con->real_escape_string($_POST['username']);
        $contact_no = $this->con->real_escape_string($_POST['contact_no']);
        $email = $this->con->real_escape_string($_POST['email']);
        $first_name = $this->con->real_escape_string($_POST['first_name']);
        $last_name = $this->con->real_escape_string($_POST['last_name']);
        $password = $this->con->real_escape_string($_POST['password']);
        $dob = $this->con->real_escape_string($_POST['dob']);
        //$profile_pic = $this->con->real_escape_string($_POST['profile_pic']);
        $role = $this->con->real_escape_string($_POST['role']);
        $verified = $this->con->real_escape_string($_POST['verified']);
        $EncryptPassword = password_hash($password, PASSWORD_DEFAULT);
        $job_title = $this->con->real_escape_string($_POST['job_title']);
        $years_exp = $this->con->real_escape_string($_POST['years_exp']);
        $specialisation = $this->con->real_escape_string($_POST['specialisation']);
        $linkedin = $this->con->real_escape_string($_POST['linkedin']);

        $filename = $_FILES["profile_pic"]["name"];
        $tempname = $_FILES["profile_pic"]["tmp_name"];
        $folder = "images/" . $filename;


        $sql = $this->con->prepare("INSERT INTO users (email, first_name, last_name, password, dob,contact_no, role, profile_pic, verified, username) 
        VALUES(?,?,?,?,?,?,?,?,?,?)");
        $sql->bind_param("sssssissis", $email, $first_name, $last_name, $EncryptPassword, $dob, $contact_no, $role, $filename, $verified, $username);
        
        if (move_uploaded_file($tempname, $folder)) {
            $msg = "Image uploaded successfully";
        } else {
            $msg = "Failed to upload image";
        }
        
        $sql->execute();
        $result = $sql->get_result();

        $last_id = $this->con->insert_id;
        
        $sql = $this->con->prepare("INSERT INTO employee (job_title, years_exp, specialisation, linkedin, user_id) 
        VALUES(?,?,?,?,?)");
        $sql->bind_param("sissi", $job_title, $years_exp, $specialisation, $linkedin, $last_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($sql == true) {
            return true;
        } else {
            return false;
        }
        $sql->close();
    }

    // Delete employee account
    public function deleteAccountRecord($user_id)
    {
        $sql = $this->con->prepare("DELETE FROM users WHERE user_id=?");
        $sql->bind_param("i", $user_id);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();

        if ($sql == true) {
            header("Location:admin.php?msg3=delete");
        } else {
            echo "Profile update failed PLEASE try again!";
        }
        $sql->close();
    }
}

$userManagementObj = new UserManagement();