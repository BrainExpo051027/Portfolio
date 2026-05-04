<?php
include 'dbcon.php';

$id = $_POST['id'] ?? 0;
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? null;
$position = $_POST['position'] ?? '';
$office = $_POST['office'] ?? '';
$age = $_POST['age'] ?? 0;
$start_date = $_POST['start_date'] ?? '';
$salary = $_POST['salary'] ?? 0;
$phone = $_POST['phone'] ?? null;
$status = $_POST['status'] ?? 'active';

class UpdateUser {
	private $conn;
	private $id;
	private $name;
	private $email;
	private $position;
	private $office;
	private $age;
	private $start_date;
	private $salary;
	private $phone;
	private $status;

	public function __construct($conn, $id, $name, $email, $position, $office, $age, $start_date, $salary, $phone, $status) {
		$this->conn = $conn;
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->position = $position;
		$this->office = $office;
		$this->age = $age;
		$this->start_date = $start_date;
		$this->salary = $salary;
		$this->phone = $phone;
		$this->status = $status;
	}

	public function updateUserInfo() {
		$sql = "UPDATE users SET name = ?, email = ?, position = ?, office = ?, age = ?, start_date = ?, salary = ?, phone = ?, status = ? WHERE id = ?";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			$stmt->bind_param("ssssissssi", $this->name, $this->email, $this->position, $this->office, $this->age, $this->start_date, $this->salary, $this->phone, $this->status, $this->id);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		} else {
			return false;
		}
	}
}

$updateInfo = new UpdateUser($conn, $id, $name, $email, $position, $office, $age, $start_date, $salary, $phone, $status);
if ($updateInfo->updateUserInfo()) {
    header('Location: usertable.php?success=1');
} else {
    header('Location: edituser.php?id=' . $id . '&error=1');
}
exit();
?>