<?php

$dataBanjir  = $_GET['resikoBanjir'];
$dataKetinggianAir   = $_GET['ketinggianAir'];

echo "Data dari url : <br> Ketinggian Air : "
     . $dataKetinggianAir . " <br> Resiko Banjir : "
     . $dataBanjir;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flood_detection";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

$sql4 = "UPDATE datasensor set ketinggianAir = $dataKetinggianAir, tanggalData=NOW() where idSensor = 0";
mysqli_query($conn, $sql4);

$sql3 = "UPDATE sensor set statusSensor = $dataBanjir where namaSensor = 'watersensor'";
mysqli_query($conn, $sql3);



mysqli_close($conn);
