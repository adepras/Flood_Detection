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

// Initialize arrays to store data for the chart
$labels = array();
$ketinggianAirData = array();

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row["tanggalData"];
    $ketinggianAirData[] = $row["ketinggianAir"];
}
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
        /* Your existing CSS styles here */
    </style>
</head>

<body>
    <div class="container py-4">
        <h1 class="text-center">Status Ketinggian Air dan Resiko Banjir</h1>
        <div class="card">
            <div class="card-body" style="max-height: calc(100vh - 420px); overflow-y:auto">
                <table class="table table-striped table-hover">
                    <!-- Your existing table content here -->
                </table>
            </div>
        </div>
        <div class="chart-container mt-4">
            <canvas id="ketinggianAirChart"></canvas>
        </div>
    </div>

    <!-- Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Retrieve data from PHP and convert to JavaScript arrays
        const labels = <?php echo json_encode($labels); ?>;
        const ketinggianAirData = <?php echo json_encode($ketinggianAirData); ?>;

        // Create a line chart using PHP-generated data
        const ctx = document.getElementById('ketinggianAirChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ketinggian Air',
                    data: ketinggianAirData,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal Waktu'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Ketinggian Air (cm)'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
