<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab6";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class FormInfoClass {
    private $lastName;
    private $firstName;
    private $middleInitial;
    private $age;
    private $contactNo;
    private $email;
    private $address;

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setMiddleInitial($middleInitial) {
        $this->middleInitial = $middleInitial;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setContactNo($contactNo) {
        $this->contactNo = $contactNo;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getMiddleInitial() {
        return $this->middleInitial;
    }

    public function getAge() {
        return $this->age;
    }

    public function getContactNo() {
        return $this->contactNo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAddress() {
        return $this->address;
    }

    public function validateForm() {
        // Add your validation logic here
        // Return true if the form is valid, false otherwise
        return true;
    }

    public function insertDataToDatabase() {
        global $conn;

        $sql = "INSERT INTO users (last_name, first_name, middle_initial, age, contact_no, email, address)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<p>Error: " . $conn->error . "</p>";
            return;
        }

        $stmt->bind_param("sssisss", $this->lastName, $this->firstName, $this->middleInitial, $this->age, $this->contactNo, $this->email, $this->address);

        if ($stmt->execute()) {
            echo "<p>Data added to the database successfully.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formInfo = new FormInfoClass();

    $formInfo->setLastName($_POST["last_name"]);
    $formInfo->setFirstName($_POST["first_name"]);
    $formInfo->setMiddleInitial($_POST["middle_initial"]);
    $formInfo->setAge($_POST["age"]);
    $formInfo->setContactNo($_POST["contact_no"]);
    $formInfo->setEmail($_POST["email"]);
    $formInfo->setAddress($_POST["address"]);

    if ($formInfo->validateForm()) {
        $formInfo->insertDataToDatabase();
        $_SESSION['form_submitted'] = true;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: <?php echo (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) ? 'none' : 'block'; ?>;
        }

        h1 {
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: white;
        }
        button {
            background-color: #008CBA;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00587a;
        }
    </style>
</head>
<body>

<?php
if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) {
    echo "<h2>Form Data</h2>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>Last Name</td><td>" . $formInfo->getLastName() . "</td></tr>";
    echo "<tr><td>First Name</td><td>" . $formInfo->getFirstName() . "</td></tr>";
    echo "<tr><td>Middle Initial</td><td>" . $formInfo->getMiddleInitial() . "</td></tr>";
    echo "<tr><td>Age</td><td>" . $formInfo->getAge() . "</td></tr>";
    echo "<tr><td>Contact No.</td><td>" . $formInfo->getContactNo() . "</td></tr>";
    echo "<tr><td>Email</td><td>" . $formInfo->getEmail() . "</td></tr>";
    echo "<tr><td>Address</td><td>" . $formInfo->getAddress() . "</td></tr>";
    echo "</table>";
    unset($_SESSION['form_submitted']);
}
?>

<form method="post" action="">
    <h1>Input Form</h1>
    <label for="last_name">Last Name *</label>
    <input type="text" name="last_name" required><br>

    <label for="first_name">First Name *</label>
    <input type="text" name="first_name" required><br>

    <label for="middle_initial">Middle Initial *</label>
    <input type="text" name="middle_initial" required><br>

    <label for="age">Age *</label>
    <input type="number" name="age" required><br>

    <label for="contact_no">Contact No. *</label>
    <input type="text" name="contact_no" required><br>

    <label for="email">Email *</label>
    <input type="email" name="email" required><br>

    <label for="address">Address *</label>
    <textarea name="address" required></textarea><br>

    <input type="submit" value="Submit">
</form>

</body>
</html>
