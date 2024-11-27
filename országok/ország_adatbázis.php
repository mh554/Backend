<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "countries";

try {
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

} catch (Exception $e) {
    echo $e->getMessage();
}
// Read the file
$file = fopen("orszagok.txt", "r");
$countries = [];

while (!feof($file)) {
    $name = trim(fgets($file));
    $population = intval(trim(fgets($file)));
    $continent = trim(fgets($file));
    $countries[] = [$name, $population, $continent];
}

fclose($file);

// Create table
$sql = "CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    population INT NOT NULL,
    continent VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table countries created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert data into the table
$stmt = $conn->prepare("INSERT INTO countries (name, population, continent) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $name, $population, $continent);

foreach ($countries as $country) {
    $name = $country[0];
    $population = $country[1];
    $continent = $country[2];
    $stmt->execute();
}

echo "Database created and data inserted successfully.";

$stmt->close();
$conn->close();
?>