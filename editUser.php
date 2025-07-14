<?php
require_once 'config.php'; // contains UPLOAD_DIR or other constants

// Fetch user data and mobile numbers
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    $user = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobileNumber'];
    $dob = $_POST['dob'];
    $oldImageName = $_POST['old_image'];



    $newImageName = $oldImageName;

    // Handle image upload
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === 0) {
        $file = $_FILES['user_image'];
        $originalName = basename($file['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            $newImageName = uniqid() . "_" . $originalName;
            $targetPath = UPLOAD_DIR . $newImageName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Delete old image
                $oldPath = UPLOAD_DIR . $oldImageName;
                if (!empty($oldImageName) && file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }
    }

    // Update user
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, mobileNumber=? , image_path=?, dob=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $email,$mobile , $newImageName, $dob, $id);
    if (!$stmt->execute()) {
        echo "User update failed: " . $stmt->error;
    }
    $stmt->close();

    echo "<script>
    parent.$.colorbox.close();
    parent.$('#usersTable').DataTable().ajax.reload();
   </script>";
   exit();

}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!------DatePicker---->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>
<body>

<div class="container">
    <div class="form-box active">
        <h2>Edit User</h2>
        <form method="POST" action="editUser.php?id=<?= $user['id'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <input type="hidden" name="old_image" value="<?= $user['image_path'] ?>">

        Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>
        Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>
        Mobile: <input type="text" name="mobileNumber" value="<?= htmlspecialchars($user['mobileNumber']) ?>" required><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="text" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required><br><br>

        Current Image:<br>
        <img src="<?= UPLOAD_DIR . htmlspecialchars($user['image_path']) ?>" width="150"><br><br>

        Change Image: <input type="file" name="user_image" accept="image/*"><br><br>

        <button type="submit">Update</button>
        </form>
    </div>
</div>

<script>
$(function() {
    $("#dob").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        maxDate: 0
    });
});
</script>

</body>
</html>
