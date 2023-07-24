<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flood_detection";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT a.*, b.* FROM datasensor a INNER JOIN sensor b ON a.idSensor = b.idSensor ORDER BY a.tanggalData DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

$data = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'tanggalData' => $row["tanggalData"],
            'ketinggianAir' => $row["ketinggianAir"],
            'resikoBanjir' => ($row["ketinggianAir"] > 500) ? 'Tinggi' : 'Rendah'
        );
    }
}

mysqli_close($conn);
header('Content-Type: application/json');
echo json_encode($data);
