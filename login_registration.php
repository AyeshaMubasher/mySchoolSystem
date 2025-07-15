<?php
session_start();
require_once 'config.php';

if(isset($_POST['register'])){
    $sName = $_POST['name'];
    $sEmail = $_POST['email'];
    $sPassword = md5($_POST['password']);
    $sMobileNumber = $_POST['mobileNumber'];
    $sDOB = $_POST['dob'];

    // Handle image
    $fileImage = $_FILES['user_image'];
    $fileImageName = basename($fileImage['name']);
    $fileImageNameToSave = uniqid() . "_" . $fileImageName;
    $fileToTarget =  UPLOAD_DIR. $fileImageNameToSave ;

    echo 
    // Check file type (basic)
    $fileImageFileType = strtolower(pathinfo($fileToTarget, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$sEmail'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        //$conn->query("INSERT INTO users (name, email, password, mobileNumber) VALUES ('$sName','$sEmail','$sPassword','$sMobileNumber')");
            if (in_array($fileImageFileType, $allowedTypes)) {
                if (move_uploaded_file($fileImage["tmp_name"], $fileToTarget)) {
                    // Insert into DB with image path
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, mobileNumber, image_path, dob) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $sName, $sEmail, $sPassword, $sMobileNumber, $fileImageNameToSave, $sDOB);
                    
                    if ($stmt->execute()) {
                        /*
                        header("Location: index.php?success=1");
                        exit();
                        */
                    } else {
                        echo "DB Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "Invalid image type. Only JPG, PNG, GIF allowed.";
            }
    }

    header("Location: index.php");
    exit();
}

if(isset($_POST['login'])){
    $sEmail = $_POST['email'];
    $sPassword = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$sEmail'");
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(md5($sPassword) === $user['password']){
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
