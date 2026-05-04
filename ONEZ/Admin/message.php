<?php
include "connection.php";
include "navbar.php";

if (!isset($_SESSION['username'])) {
  $_SESSION['username'] = ''; // Prevent undefined index
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Message</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .container {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .left_box {
      width: 350px;
      background-color: #8ecdd2;
      display: flex;
      flex-direction: column;
      padding: 10px;
    }

    .left_box2 {
      background-color: #537890;
      border-radius: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      padding: 10px;
    }

    .left_box2 form {
      margin-bottom: 10px;
      display: flex;
      gap: 5px;
    }

    .left_box2 input {
      flex: 1;
      padding: 8px;
      border-radius: 5px;
      border: none;
    }

    .left_box2 button {
      padding: 8px;
      background: #02c5b6;
      border: none;
      color: white;
      border-radius: 5px;
    }

    .list {
      overflow-y: auto;
      flex-grow: 1;
      color: white;
    }

    .right_box {
      flex-grow: 1;
      background-color: #8ecdd2;
      display: flex;
      flex-direction: column;
      padding: 10px;
    }

    .right_box2 {
      background-color: #537890;
      border-radius: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      padding: 20px;
      color: white;
    }

    .chat-header {
      text-align: center;
      margin-bottom: 10px;
    }

    .msg {
      flex-grow: 1;
      overflow-y: auto;
      padding-right: 10px;
    }

    .chat {
      display: flex;
      align-items: flex-start;
      margin-bottom: 10px;
    }

    .user {
      justify-content: flex-start;
    }

    .admin {
      justify-content: flex-end;
      text-align: right;
    }

    .user .chatbox {
      background-color: #821b69;
      border-top-left-radius: 0;
    }

    .admin .chatbox {
      background-color: #423471;
      border-top-right-radius: 0;
    }

    .chatbox {
      max-width: 60%;
      padding: 13px 15px;
      border-radius: 10px;
      color: white;
      word-wrap: break-word;
    }

    .chat img {
      height: 40px;
      width: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 10px;
    }

    .message-input {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .message-input input {
      flex: 1;
      padding: 10px;
      border-radius: 5px;
      border: none;
      background-color: #fff;
      color: #000;
    }

    .message-input button {
      padding: 10px 20px;
      background-color: #02c5b6;
      border: none;
      border-radius: 5px;
      color: white;
    }

    tr:hover {
      background-color: #1e3f54;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Left Panel -->
  <div class="left_box">
    <div class="left_box2">
      <form method="post">
        <input type="text" name="username" id="uname" placeholder="Enter Username">
        <button type="submit" name="submit">SHOW</button>
      </form>
      <div class="list">
        <?php
        $sql1 = mysqli_query($conn, "SELECT student.pic, message.username FROM student INNER JOIN message ON student.username=message.username GROUP BY username ORDER BY status;");
        echo "<table id='table'>";
        while ($res1 = mysqli_fetch_assoc($sql1)) {
          echo "<tr>";
          echo "<td><img height=50 width=50 src='images/" . $res1['pic'] . "' style='border-radius:50%;'></td>";
          echo "<td style='padding-left:10px;'>" . $res1['username'] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
        ?>
      </div>
    </div>
  </div>

  <!-- Right Panel -->
  <div class="right_box">
    <div class="right_box2">
      <?php
      if (isset($_POST['submit']) && $_POST['username'] != '') {
        $_SESSION['username'] = $_POST['username'];
        mysqli_query($conn, "UPDATE message SET status='yes' WHERE sender='student' AND username='$_SESSION[username]' ");
      }

      if ($_SESSION['username'] != '') {
        if (isset($_POST['submit1']) && $_POST['message'] != '') {
          mysqli_query($conn, "INSERT INTO message VALUES('', '$_SESSION[username]', '" . mysqli_real_escape_string($conn, $_POST['message']) . "', 'no', 'admin');");
        }

        $res = mysqli_query($conn, "SELECT * FROM message WHERE username='$_SESSION[username]'");

        // Get student profile picture
        $studentPicResult = mysqli_query($conn, "SELECT pic FROM student WHERE username='$_SESSION[username]'");
        $studentData = mysqli_fetch_assoc($studentPicResult);
        $studentPic = $studentData['pic'] ?? 'default.png';

        echo "<div class='chat-header'><h3>" . $_SESSION['username'] . "</h3></div>";
        echo "<div class='msg'>";
        while ($row = mysqli_fetch_assoc($res)) {
          if ($row['sender'] == 'student') {
            echo "<div class='chat user'>";
            echo "<img src='images/{$studentPic}'>";
            echo "<div class='chatbox'>" . htmlspecialchars($row['message']) . "</div>";
            echo "</div>";
          } else {
            echo "<div class='chat admin'>";
            echo "<div class='chatbox'>" . htmlspecialchars($row['message']) . "</div>";
            echo "<img src='images/" . ($_SESSION['pic'] ?? 'admin.png') . "'>";
            echo "</div>";
          }
        }
        echo "</div>";
      ?>
        <form method="post" class="message-input">
          <input type="text" name="message" placeholder="Write Message..." required>
          <button type="submit" name="submit1">Send</button>
        </form>
      <?php
      }
      ?>
    </div>
  </div>
</div>

<script>
  // Autofill username input on row click
  const table = document.getElementById('table');
  for (let i = 0; i < table.rows.length; i++) {
    table.rows[i].onclick = function () {
      document.getElementById("uname").value = this.cells[1].innerText;
    };
  }
</script>

</body>
</html>
