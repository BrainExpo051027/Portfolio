<?php
include 'dbcon.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

class DeleteUser {
	private $id;
	private $conn;

	public function __construct($id, $conn) {
		$this->conn = $conn;
		$this->id = $id;
	}

	public function delUser() {
		$sql = "DELETE FROM users WHERE id = ?";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			$stmt->bind_param("i", $this->id);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		} else {
			return false;
		}
	}
}

$userdel = new DeleteUser($id, $conn);
if ($userdel->delUser()) {
    header('Location: usertable.php?success=1');
} else {
    header('Location: usertable.php?error=1');
}
exit();
?>