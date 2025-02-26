<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up/Login</title>
    <script src="js/validation.js" defer></script>
</head>
<body>
    <header></header>
    <?php
    if (isset($_SESSION['error_exist'])) {
        echo "<p style='color:red;'>".$_SESSION['error_exist']."</p>";
        unset($_SESSION['error_exist']); 
    }
    ?>
    <main>
        <h1>Sign Up for Member</h1>
        <form method="post" action="backend/register.php" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <span id="username_error" style="color:red;"></span>
            <br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span id="email_error" style="color:red;"></span>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span id="password_error" style="color:red;"></span>
            <br><br>
            <label for="profile_photo">Profile Photo:</label>
            <input type="file" id="profile_photo" name="profile_photo">
            <br><br>
            <button type="submit">Register</button>
            <button type="reset">Cancel</button>
        </form>
        <br><br><br>
        <?php
        if (isset($_SESSION['error_not_exist'])) {
            echo "<p style='color:red;'>".$_SESSION['error_not_exist']."</p>";
            unset($_SESSION['error_not_exist']); 
        }
        if (isset($_SESSION['error_pwd'])) {
            echo "<p style='color:red;'>".$_SESSION['error_pwd']."</p>";
            unset($_SESSION['error_pwd']); 
        }
        ?>
        <h1>Login for Admin</h1>
        <form method="post" action="backend/adminlogin.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <span id="username_error" style="color:red;"></span>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span id="password_error" style="color:red;"></span>
            <br><br>
            <button type="submit">Login</button>
            <button type="reset">Cancel</button>
        </form>
    </main>
    <footer></footer>
</body>
</html>