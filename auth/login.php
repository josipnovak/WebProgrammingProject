<?php
    include "includes/db.php";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!$username || !$password) {
            $error = "Username and password are required.";
        } 
        else{
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0){
                $stmt->bind_result($id, $hashed_password, $role);
                $stmt->fetch();

                if(password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    header("Location: index.php");
                }
                else {
                    $error = "Invalid password.";
                }
            }
            else {
                $error = "No user found with that username.";
            }
        }
    }
?>