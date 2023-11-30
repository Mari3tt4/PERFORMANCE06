<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab6";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Instantiate the FormInfoClass
    $formInfo = new FormInfoClass();

    // Set values using form data
    $formInfo->setLastName($_POST["last_name"]);
    $formInfo->setFirstName($_POST["first_name"]);
    $formInfo->setMiddleInitial($_POST["middle_initial"]);
    $formInfo->setAge($_POST["age"]);
    $formInfo->setContactNo($_POST["contact_no"]);
    $formInfo->setEmail($_POST["email"]);
    $formInfo->setAddress($_POST["address"]);

    // Insert data into MySQL table
    $sql = "INSERT INTO users (last_name, first_name, middle_initial, age, contact_no, email, address)
            VALUES ('" . $formInfo->getLastName() . "', '" . $formInfo->getFirstName() . "', '" . $formInfo->getMiddleInitial() . "', 
            '" . $formInfo->getAge() . "', '" . $formInfo->getContactNo() . "', '" . $formInfo->getEmail() . "', '" . $formInfo->getAddress() . "')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();

// Rest of the code for FormInfoClass and HTML form remains unchanged

?>
