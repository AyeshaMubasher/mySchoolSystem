<?php

require_once 'config.php';

$result = $conn->query("SELECT id,name,email,mobileNumber,dob FROM users");

$users = [];

while ($row = $result->fetch_assoc()){
    $users[] = $row;
}

echo json_encode(['data'=>$users]);

?>