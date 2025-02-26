<?php
session_start();
require 'database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: adminlogin.php');
    exit;
}

// Fetch all members
$stmt = $pdo->query("SELECT * FROM members");
$members = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Member Listing</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Profile Photo</th>
        </tr>
        <?php foreach ($members as $member): ?>
        <tr>
            <td><?= $member['id'] ?></td>
            <td><?= $member['username'] ?></td>
            <td><?= $member['email'] ?></td>
            <td>
                <?php if ($member['profile_photo']): ?>
                    <img src="<?= $member['profile_photo'] ?>" width="50">
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Search Members</h2>
    <form method="GET" action="admin.php">
        <label>Search: <input type="text" name="search"></label>
        <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['search'])) {
        $search = "%{$_GET['search']}%";
        $stmt = $pdo->prepare("SELECT * FROM members WHERE username LIKE ? OR email LIKE ?");
        $stmt->execute([$search, $search]);
        $results = $stmt->fetchAll();

        if ($results) {
            echo "<h3>Search Results</h3>";
            foreach ($results as $result) {
                echo "<p>{$result['username']} - {$result['email']}</p>";
            }
        } else {
            echo "<p>No results found.</p>";
        }
    }
    ?>
</body>
</html>