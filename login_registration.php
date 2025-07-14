<?php
session_start();
require_once 'config.php';

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $mobileNumber = $_POST['mobileNumber'];
    $dob = $_POST['dob'];

    // Handle image
    $image = $_FILES['user_image'];
    $imageName = basename($image['name']);
    $imageNameToSave = uniqid() . "_" . $imageName;
    $targetFile =  UPLOAD_DIR. $imageNameToSave ;

    echo 
    // Check file type (basic)
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        //$conn->query("INSERT INTO users (name, email, password, mobileNumber) VALUES ('$name','$email','$password','$mobileNumber')");
            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                    // Insert into DB with image path
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, mobileNumber, image_path, dob) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $name, $email, $password, $mobileNumber, $imageNameToSave, $dob);
                    
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
