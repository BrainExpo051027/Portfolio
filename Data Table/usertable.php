
<?php
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Table</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                 <li>
                    <a href="index.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="usertable.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Table</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
                    <form method="GET" action="usertable.php" style="display: flex; align-items: center;">
                        <input type="text" name="search" placeholder="Search by name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="flex:1; height:40px; border-radius:40px; padding:5px 20px; font-size:18px; border:1px solid #999;">
                        <button type="submit" style="background:none; border:none; margin-left:-35px; cursor:pointer;"><ion-icon name="search-outline"></ion-icon></button>
                    </form>
                </div>
            </div>
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>User Table</h2>
                        <a href="adduser.php" class="btn">Add User</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start Date</th>
                                <th>Salary</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include('dbcon.php');
                                class SelectUserData {
                                    private $conn;
                                    public function __construct($conn) {
                                        $this->conn = $conn;
                                        if ($this->conn->connect_error) {
                                            die("Connection failed: " . $this->conn->connect_error);
                                        }
                                    }
                                    public function getUser() {
                                        $sql = "SELECT * FROM users ORDER BY created_at DESC";
                                        $result = $this->conn->query($sql);
                                        $users = array();
                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $users[] = $row;
                                            }
                                        }
                                        return $users;
                                    }
                                }
                                $search = isset($_GET['search']) ? $_GET['search'] : '';
                                $userData = new SelectUserData($conn);
                                if ($search !== '') {
                                    $users = array_filter($userData->getUser(), function($user) use ($search) {
                                        return stripos($user['name'], $search) !== false;
                                    });
                                } else {
                                    $users = $userData->getUser();
                                }
                                foreach ($users as $user) {
                            ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                                <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
                                            </div>
                                            <?php echo htmlspecialchars($user['name']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['position']); ?></td>
                                    <td><?php echo htmlspecialchars($user['office']); ?></td>
                                    <td><?php echo htmlspecialchars($user['age']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['start_date'])); ?></td>
                                    <td>$<?php echo number_format($user['salary'], 0); ?></td>
                                    <td>
                                        <span class="status <?php echo $user['status'] === 'active' ? 'delivered' : 'pending'; ?>">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="deleteuser.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                            <a href="edituser.php?id=<?php echo $user['id']; ?>" class="btn btn-info">Update</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>