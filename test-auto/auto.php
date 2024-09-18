<?php

// Kapcsolódás az adatbázishoz
$servername = "localhost";
$username = "root"; // állítsd be a megfelelő felhasználónevet
//$password = "password"; // állítsd be a megfelelő jelszót

// Kapcsolódás létrehozása
$conn = new mysqli($servername, $username);

// Ellenőrizzük a kapcsolatot
if ($conn->connect_error) {
    die("Kapcsolódás sikertelen: " . $conn->connect_error);
}

// Adatbázis létrehozása
$sql = "CREATE DATABASE IF NOT EXISTS auto";
if ($conn->query($sql) === TRUE) {
    echo "Adatbázis sikeresen létrehozva.\n";
} else {
    echo "Hiba az adatbázis létrehozásakor: " . $conn->error;
}

// Adatbázis kiválasztása
$conn->select_db("auto");

// Tábla létrehozása: vizsga_gepjarmu
$sql = "CREATE TABLE IF NOT EXISTS vizsga_gepjarmu (
    rendszam VARCHAR(7) PRIMARY KEY,
    marka VARCHAR(20),
    tipus VARCHAR(20),
    tulaj_neve VARCHAR(50),
    fogyasztas FLOAT
)";
$conn->query($sql);

// Tábla létrehozása: vizsga_utvonal
$sql = "CREATE TABLE IF NOT EXISTS vizsga_utvonal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gepjarmuID VARCHAR(7),
    honnan VARCHAR(50),
    hova VARCHAR(50),
    km INT,
    FOREIGN KEY (gepjarmuID) REFERENCES vizsga_gepjarmu(rendszam)
)";
$conn->query($sql);

// Tesztadatok feltöltése: vizsga_gepjarmu
$tesztadatok_gepjarmu = [
    ['ABC1234', 'Opel', 'Corsa', 'Kovács János', 5.5],
    ['DEF5678', 'BMW', '316', 'Nagy Péter', 8.0],
    ['GHI9101', 'Audi', 'A4', 'Szabó Anna', 7.0],
];

foreach ($tesztadatok_gepjarmu as $adat) {
    $sql = "INSERT INTO vizsga_gepjarmu (rendszam, marka, tipus, tulaj_neve, fogyasztas) VALUES ('$adat[0]', '$adat[1]', '$adat[2]', '$adat[3]', $adat[4])";
    $conn->query($sql);
}

// Tesztadatok feltöltése: vizsga_utvonal
$tesztadatok_utvonal = [
    ['ABC1234', 'Budapest', 'Szeged', 200],
    ['DEF5678', 'Debrecen', 'Pécs', 250],
    ['GHI9101', 'Miskolc', 'Győr', 180],
];

foreach ($tesztadatok_utvonal as $adat) {
    $sql = "INSERT INTO vizsga_utvonal (gepjarmuID, honnan, hova, km) VALUES ('$adat[0]', '$adat[1]', '$adat[2]', $adat[3])";
    $conn->query($sql);
}

// Adatok megjelenítése: vizsga_gepjarmu
$sql = "SELECT * FROM vizsga_gepjarmu";
$result;