<?php
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
 
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="index.php">
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
                    <a href="../index.html">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Portfolio</span>
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
            
            <!-- User Info -->
            <div style="position: absolute; bottom: 20px; left: 20px; right: 20px; padding: 16px; background: var(--gray-50); border-radius: 12px; border: 1px solid var(--border-light);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 2)); ?>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.875rem;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                        <div style="color: var(--text-secondary); font-size: 0.75rem;"><?php echo htmlspecialchars($_SESSION['user_position']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <form method="GET" action="index.php" style="display: flex; align-items: center;">
                        <input type="text" name="search" placeholder="Search by name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="flex:1; height:40px; border-radius:40px; padding:5px 20px; font-size:18px; border:1px solid #999;">
                        <button type="submit" style="background:none; border:none; margin-left:-35px; cursor:pointer;"><ion-icon name="search-outline"></ion-icon></button>
                    </form>
                </div>
            </div>


            <?php
                include('dbcon.php');
                // Get users
                $users = [];
                $totalUsers = 0;
                $totalEarnings = 0;
                $activeUsers = 0;
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                
                // Use the new users table and dashboard_stats view
                if ($search !== '') {
                    $sql = "SELECT * FROM users WHERE name LIKE ? AND status = 'active'";
                    $stmt = $conn->prepare($sql);
                    $searchParam = "%" . $search . "%";
                    $stmt->bind_param("s", $searchParam);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $sql = "SELECT * FROM users WHERE status = 'active' ORDER BY created_at DESC LIMIT 10";
                    $result = $conn->query($sql);
                }
                
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $users[] = $row;
                        $totalUsers++;
                        $totalEarnings += floatval($row['salary']);
                        if ($row['status'] === 'active') {
                            $activeUsers++;
                        }
                    }
                }
                
                // Get dashboard statistics
                $statsSql = "SELECT * FROM dashboard_stats";
                $statsResult = $conn->query($statsSql);
                $stats = $statsResult->fetch_assoc();
            ?>
            <div class="cardBox">
                <div class="card fade-in">
                    <div>
                        <div class="numbers"><?php echo $stats['total_users'] ?? $totalUsers; ?></div>
                        <div class="cardName">Total Users</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
                <div class="card fade-in">
                    <div>
                        <div class="numbers"><?php echo $stats['active_users'] ?? $activeUsers; ?></div>
                        <div class="cardName">Active Users</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                </div>
                <div class="card fade-in">
                    <div>
                        <div class="numbers">$<?php echo number_format($stats['avg_salary'] ?? 0, 0); ?></div>
                        <div class="cardName">Avg Salary</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="trending-up-outline"></ion-icon>
                    </div>
                </div>
                <div class="card fade-in">
                    <div>
                        <div class="numbers">$<?php echo number_format($stats['total_salary'] ?? $totalEarnings, 0); ?></div>
                        <div class="cardName">Total Payroll</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) { ?>
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
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>