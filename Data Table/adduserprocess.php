<?php
include('dbcon.php');

// Get form data
$name = $_POST['name'] ?? '';
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
$email = $_POST['email'] ?? null;
$position = $_POST['position'] ?? '';
$office = $_POST['office'] ?? '';
$age = $_POST['age'] ?? 0;
$start_date = $_POST['start_date'] ?? '';
$salary = $_POST['salary'] ?? 0;
$phone = $_POST['phone'] ?? null;
$status = $_POST['status'] ?? 'active';

class AddUser {
	private $conn;
	private $name;
	private $password;
	private $email;
	private $position;
	private $office;
	private $age;
	private $start_date;
	private $salary;
	private $phone;
	private $status;

	public function __construct($conn, $name, $password, $email, $position, $office, $age, $start_date, $salary, $phone, $status) {
		$this->conn = $conn;
		$this->name = $name;
		$this->password = $password;
		$this->email = $email;
		$this->position = $position;
		$this->office = $office;
		$this->age = $age;
		$this->start_date = $start_date;
		$this->salary = $salary;
		$this->phone = $phone;
		$this->status = $status;
	}

	public function addUser() {
		$sql = "INSERT INTO users (name, password, email, position, office, age, start_date, salary, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			$stmt->bind_param("sssssissss", $this->name, $this->password, $this->email, $this->position, $this->office, $this->age, $this->start_date, $this->salary, $this->phone, $this->status);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		} else {
			return false;
		}
	}
}

$addUser = new AddUser($conn, $name, $password, $email, $position, $office, $age, $start_date, $salary, $phone, $status);
if ($addUser->addUser()) {
    header('Location: usertable.php?success=1');
} else {
    header('Location: adduser.php?error=1');
}
exit();
?>