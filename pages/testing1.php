<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'member_db';
$user = 'root';
$pass = '';

try {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['signup'])) {
            // Signup logic
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM members WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $_SESSION['error_exist'] = "Email already exists!";
            } else {
                // Insert new member
                $stmt = $conn->prepare("INSERT INTO members (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $password);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Registration successful!";
                } else {
                    $_SESSION['error_exist'] = "Registration failed!";
                }
            }
            $stmt->close();
        } elseif (isset($_POST['login'])) {
            // Login logic
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT id, password FROM members WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['success'] = "Login successful!";
            } else {
                $_SESSION['error_not_exist'] = "Invalid email or password!";
            }
            $stmt->close();
        } elseif (isset($_POST['manual_input'])) {
            // Manual input logic
            $name = $_POST['manual_name'];
            $email = $_POST['manual_email'];
            $password = password_hash($_POST['manual_password'], PASSWORD_DEFAULT);
            $profile_photo = $_POST['manual_profile_photo'];

            // Insert manually inputted member
            $stmt = $conn->prepare("INSERT INTO members (name, email, password, profile_photo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password, $profile_photo);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Manual input successful!";
            } else {
                $_SESSION['error_exist'] = "Manual input failed!";
            }
            $stmt->close();
        }
    }

    // Fetch members for listing
    $members = [];
    $result = $conn->query("SELECT id, name, email, profile_photo FROM members");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
    }
    $conn->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Member Maintenance</title>
</head>
<body>
    <h1>Member Registration</h1>
    <form method="post" action="testing1.php">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>

    <h1>Member Login</h1>
    <form method="post" action="testing1.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <h1>Manual Member Input</h1>
    <form method="post" action="testing1.php">
        <input type="text" name="manual_name" placeholder="Name" required>
        <input type="email" name="manual_email" placeholder="Email" required>
        <input type="password" name="manual_password" placeholder="Password" required>
        <input type="text" name="manual_profile_photo" placeholder="Profile Photo URL">
        <button type="submit" name="manual_input">Submit Manual Input</button>
    </form>

    <h1>Member Listing</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Profile Photo</th>
        </tr>
        <?php foreach ($members as $member): ?>
        <tr>
            <td><?php echo $member['id']; ?></td>
            <td><?php echo $member['name']; ?></td>
            <td><?php echo $member['email']; ?></td>
            <td><?php echo $member['profile_photo'] ? '<img src="'.$member['profile_photo'].'" width="50">' : 'No Photo'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>