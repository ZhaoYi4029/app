<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Handle profile photo upload
    $profile_photo = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $profile_photo = $upload_dir . basename($_FILES['profile_photo']['name']);
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo);
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO members (username, password, email, profile_photo) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $profile_photo]);

    echo "Registration successful!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Registration</title>
</head>
<body>
    <h1>Member Registration</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Profile Photo: <input type="file" name="profile_photo"></label><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>