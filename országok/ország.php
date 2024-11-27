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

function list_all_countries($conn) {
    $sql = "SELECT name, population FROM countries";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        echo $row["name"]. " - Population: " . $row["population"]. "<br>";
    }
}

function show_population_stats($conn) {
    $sql = "SELECT population FROM countries";
    $result = $conn->query($sql);
    $large = 0;
    $small = 0;
    while($row = $result->fetch_assoc()) {
        if ($row["population"] > 50000) {
            $large++;
        } else {
            $small++;
        }
    }
    echo "Large population countries: $large<br>";
    echo "Small population countries: $small<br>";
}

function show_extreme_population_countries($conn) {
    $sql = "SELECT name, population FROM countries ORDER BY population DESC";
    $result = $conn->query($sql);
    $largest = $result->fetch_assoc();
    $result->data_seek($result->num_rows - 1);
    $smallest = $result->fetch_assoc();
    echo "Largest country: " . $largest["name"] . " - Population: " . $largest["population"] . "<br>";
    echo "Smallest country: " . $smallest["name"] . " - Population: " . $smallest["population"] . "<br>";
}

function show_average_population($conn) {
    $sql = "SELECT AVG(population) as avg_population FROM countries";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "Average population: " . $row["avg_population"] . "<br>";
}

function list_european_countries($conn) {
    $sql = "SELECT name, population FROM countries WHERE continent = 'Európa'";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        echo $row["name"]. " - Population: " . $row["population"]. "<br>";
    }
}

function save_european_countries_to_file($conn) {
    $sql = "SELECT name, population FROM countries WHERE continent = 'Európa'";
    $result = $conn->query($sql);
    $file = fopen("europaiak.txt", "w");
    while($row = $result->fetch_assoc()) {
        fwrite($file, $row["name"]. " - Population: " . $row["population"]. "\n");
    }
    fclose($file);
    echo "European countries saved to europaiak.txt<br>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Country Menu</title>
</head>
<body>
    <form method="post">
        <label for="option">Menu:</label>
        <select name="option" id="option">
            <option value="1">List all countries</option>
            <option value="2">Show number of large and small population countries</option>
            <option value="3">Show the country with the largest and smallest population</option>
            <option value="4">Show average population</option>
            <option value="5">List European countries</option>
            <option value="6">Save European countries to a file</option>
        </select>
        <input type="submit" value="Choose">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $choice = $_POST['option'];

        // Assuming $conn is your database connection
        switch(trim($choice)) {
            case 1:
                list_all_countries($conn);
                break;
            case 2:
                show_population_stats($conn);
                break;
            case 3:
                show_extreme_population_countries($conn);
                break;
            case 4:
                show_average_population($conn);
                break;
            case 5:
                list_european_countries($conn);
                break;
            case 6:
                save_european_countries_to_file($conn);
                break;
            default:
                echo "Invalid option\n";
                break;
        }

        $conn->close();
    }
    ?>
</body>
</html>