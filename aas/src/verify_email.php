<?php
    include_once "database.php";


    session_start();
    
    $dbObj = new Database();
    $mysqli = $dbObj->connection();

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $sql = $mysqli->prepare("SELECT * FROM users WHERE token=? LIMIT 1");
        $sql->bind_param("s", $token);
        $sql->execute();
        $result = $sql->get_result();
		
        if ($result->num_rows > 0){
            $user = mysqli_fetch_assoc($result);
            $query = $mysqli->prepare("UPDATE users SET verified=1 WHERE token=?");
            $query->bind_param("s", $token);

           if($query->execute()){
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['verified'] = true;
                header('location: index.php');
                exit(0);
            }
        } else {
            echo "User not found!";
        }
        $sql->close();
    } else {
        echo "No token provided!";
    }
    
?>