<?php

// config.php

session\_start();



$host = 'localhost';

$dbname = 'attendance\_system';

$username = 'root';

$password = '';



try {

&nbsp;   $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

&nbsp;   $pdo->setAttribute(PDO::ATTR\_ERRMODE, PDO::ERRMODE\_EXCEPTION);

} catch(PDOException $e) {

&nbsp;   die("Connection failed: " . $e->getMessage());

}
