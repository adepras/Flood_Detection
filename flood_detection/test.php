<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eslolin";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
die("Koneksi gagal: " . mysqli_connect_error());
} else {
die("Koneksi berhasil: API Server is running");
}