<?php
session_start();
require_once 'config.php';

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $mobileNumber = $_POST['mobileNumber'];

    //echo 'form data=  name: '.$name.' email: '.$email. 'password: '.password. ' mobileNumber: '.mobileNumber;
    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    //echo 'check email query result: '.$checkEmail;
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO users (name, email, password, mobileNumber) VALUES ('$name','$email','$password','$mobileNumber')");
    }

    header("Location: index.php");
    exit();
}

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(md5($password) === $user['password']){
            $_SESSION['id']= $user['id'];
            $_SESSION['name']= $user['name'];
            $_SESSION['email']= $user['email'];
            $_SESSION['mobileNumber']= $user['mobileNumber'];
            header("Location: homePage.php");
            exit();
        }
    }

    $_SESSION['login_error']= 'Incorrect email or password';
    $_SESSION['active_form']= 'login';
    header("Location: index.php");
    exit();

}

?>
