<?php

require_once 'config.php';

$result = $conn->query("SELECT name,email,mobileNumber FROM users");

$users = [];

while ($row = $result->fetch_assoc()){
    $users[] = $row;
}

echo json_encode(['data'=>$users]);

?>