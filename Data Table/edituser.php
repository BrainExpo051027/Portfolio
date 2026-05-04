<?php 
require_once 'auth_check.php';
include('dbcon.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

class SelectUserData {
    private $conn;
    private $id;
    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function getUser() {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        $stmt->close();
        return $users;
    }
}
$userData = new SelectUserData($conn, $id);
$users = $userData->getUser(); // Retrieve user data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="updateuser.php" method="POST">
                <?php 
                foreach ($users as $user) {
                ?>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
                    <h1>Edit User</h1>
                    <span>Update user information below</span>
                    
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required />
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($user['position']); ?>" required />
                    </div>
                    
                    <div class="form-group">
                        <label for="office">Office:</label>
                        <input type="text" name="office" class="form-control" value="<?php echo htmlspecialchars($user['office']); ?>" required />
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($user['age']); ?>" required min="18" max="100" />
                    </div>
                    
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo $user['start_date']; ?>" required />
                    </div>
                    
                    <div class="form-group">
                        <label for="salary">Salary:</label>
                        <input type="number" name="salary" class="form-control" value="<?php echo htmlspecialchars($user['salary']); ?>" required min="0" step="0.01" />
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" />
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" class="form-control" required>
                            <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                <?php }?>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-success">Update User</button>
                    <a href="usertable.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>