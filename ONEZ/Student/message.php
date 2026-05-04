<?php
include "connection.php";
include "navbar.php";

// Ensure user is logged in
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['login_user'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert new message
    $query = "INSERT INTO `library`.`message` (username, message, status, sender) 
              VALUES ('$user', '$message', 'no', 'student')";
    mysqli_query($conn, $query);
}

// Fetch messages
$res = mysqli_query($conn, "SELECT * FROM `library`.`message` WHERE username = '$user'");
mysqli_query($conn,"UPDATE message set status='yes' where sender='admin' and username='$_SESSION[login_user]';");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message</title>
    <style type="text/css">
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 500px;
            height: 600px;
            background-color: #000;
            opacity: 0.9;
            color: white;
            margin: 40px auto;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
        }

        .header {
            height: 70px;
            background-color: #2eac8b;
            text-align: center;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .msg {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .form-control {
            height: 47px;
            width: 79%;
            padding: 10px;
            border-radius: 5px;
            border: none;
        }

        form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #111;
            border-top: 1px solid #333;
        }

        .btn-info {
            background-color: #02c5b6;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-info:hover {
            background-color: #029e91;
        }

        .chat {
            display: flex;
            align-items: flex-end;
            margin-bottom: 15px;
        }

        .chat img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
        }

        .chatbox {
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            word-wrap: break-word;
        }

        .admin {
            flex-direction: row;
        }

        .admin .chatbox {
            background-color: #423471;
            color: white;
            margin-left: 10px;
        }

        .user {
            flex-direction: row-reverse;
            text-align: right;
        }

        .user .chatbox {
            background-color: #821b69;
            color: white;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <h3>Admin</h3>
    </div>

    <div class="msg">
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <?php if ($row['sender'] == 'student'): ?>
                <div class="chat user">
                    <img src="images/<?php echo htmlspecialchars($_SESSION['pic']); ?>" alt="Student">
                    <div class="chatbox">
                        <?php echo htmlspecialchars($row['message']); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="chat admin">
                    <img src="images/<?php echo htmlspecialchars($_SESSION['pic']); ?>" alt="Admin">
                    <div class="chatbox">
                        <?php echo htmlspecialchars($row['message']); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>

    <div style="height:100px; padding-top: 10px;">
        <form action="" method="post">
            <input type="text" name="message" class="form-control" required placeholder="Write a message...">
            <button class="btn btn-info btn-lg" type="submit" name="submit">
                <span class="glyphicon glyphicon-send"></span>&nbsp;Send
            </button>
        </form>
    </div>
</div>

</body>
</html>