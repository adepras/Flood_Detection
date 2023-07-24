<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flood_detection";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT a.*, b.* FROM datasensor a INNER JOIN sensor b ON a.idSensor = b.idSensor";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Ketinggian Air dan Resiko Banjir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 20px;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .high-risk {
            color: #fff;
            background-color: #dc3545;
        }

        .low-risk {
            color: #fff;
            background-color: #28a745;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <h1 class="text-center">Status Ketinggian Air dan Resiko Banjir</h1>
        <div class="card">
            <div class="card-body" style="max-height: calc(100vh - 420px); overflow-y:auto">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Waktu</th>
                            <th>Ketinggian Air</th>
                            <th>Resiko Banjir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Balikkan nilai ketinggian air
                                $ketinggian_rendah = 0;
                                $ketinggian_maksimal = 22;
                                $ketinggian_air = $ketinggian_maksimal - $row["ketinggianAir"];

                                echo "<tr>";
                                echo "<td>" . $row["tanggalData"] . "</td>";
                                echo "<td>" . $ketinggian_air . " cm</td>";
                                echo "<td>";

                                if ($ketinggian_air > 500) {
                                    echo '<span class="badge badge-danger high-risk">Tinggi</span>';
                                } else {
                                    echo '<span class="badge badge-success low-risk">Rendah</span>';
                                }

                                echo "</td>" . "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="3">0 results</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function updateTable() {
            $.ajax({
                url: 'get_data.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        var tableBody = '';

                        $.each(data, function (index, item) {
                            var resikoClass = (item.resikoBanjir === 'Tinggi') ? 'high-risk' : 'low-risk';

                            tableBody += '<tr>' +
                                '<td>' + item.tanggalData + '</td>' +
                                '<td>' + item.ketinggianAir + ' cm</td>' +
                                '<td><span class="badge ' + resikoClass + '">' + item.resikoBanjir + '</span></td>' +
                                '</tr>';
                        });

                        $('tbody').html(tableBody);
                    } else {
                        $('tbody').html('<tr><td colspan="3">0 hasil</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Perbarui tabel setiap 5 detik
        setInterval(updateTable, 5000);
    </script>
</body>

</html>
