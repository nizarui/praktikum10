<?php
include('koneksicovid.php');
$label = ["India", "Japan", "S.Korea", "Turkey", "Vietnam", "Taiwan", "Iran", "Indonesia", "Malaysia", "Israel"];
/*
for ($country = 1; $country < 11; $country++) {
    $query = mysqli_query($koneksi, "select total_cases from tb_covid where id='$country'");
    $row = $query->fetch_array();
    $jumlah_produk[] = $row['total_cases'];
}
*/
$data = mysqli_query($koneksi, "select * from tb_covid");
while ($row = mysqli_fetch_array($data)) {
    $nama_produk[] = $row['country'];

    $query = mysqli_query($koneksi, "select sum(total_deaths) as total_deaths from tb_covid where id='" . $row['id'] . "'");
    $row = $query->fetch_array();
    $jumlah_produk[] = $row['total_deaths'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Total Deaths</title>
    <script type="text/javascript" src="Chart.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <style>
        #canvas-holder {
            display: flex;
            /*justify-content: center;
            /* Horizontally center */
            /*align-items: center;
            /* Vertically center */
            width: 100%;
            height: 100%;
            /* Adjust the height as needed */
        }

        #section {
            padding-top: 125px;
        }

        #section1 {
            padding-right: 250px;
            padding-left: 250px;
            padding-top: 10px;
            padding-bottom: 50px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">

        <!-- Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="totalcases.php">Total Cases</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="totaldeath.php">Total Death</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="totalrecovered.php">Total Recovered</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="activecases.php">Active Cases</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="totaltests.php">Total Tests</a>
            </li>
        </ul>

    </nav>
    <div id="section" class="text-center">
        <h1>Top 10 Countries of Covid Total Death</h1>
    </div>
    <div id="section1">
        <h3>Line Chart</h3>
        <div class="border border-secondary shadow p-4 mb-4 bg-white">
            <canvas id="myLine"></canvas>
        </div>
    </div>

    <div id="section1">
        <h3>Bar Chart</h3>
        <div class="border border-secondary shadow p-4 mb-4 bg-white">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <div id="section1">
        <h3>Doughnut Chart</h3>
        <div id="canvas-holder" class="border border-secondary shadow p-4 mb-4 bg-white">
            <canvas id="chart-area"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($label); ?>,
                datasets: [{
                    label: 'Grafik Total Case Covid-19',
                    data: <?php echo json_encode($jumlah_produk); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>


    <script>
        var config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: <?php echo json_encode($jumlah_produk); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 99, 255, 0.2)',
                        'rgba(54, 162, 64, 0.2)',
                        'rgba(255, 206, 153, 0.2)',
                        'rgba(75, 192, 128, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 99, 255, 1)',
                        'rgba(54, 162, 64, 1)',
                        'rgba(255, 206, 153, 1)',
                        'rgba(75, 192, 128, 1)'
                    ],
                    label: 'Presentase Penjualan Barang'
                }],
                labels: <?php echo json_encode($nama_produk); ?>
            },
            options: {
                responsive: true
            }
        };

        window.onload = function() {
            var ctx = document.getElementById('chart-area').getContext('2d');
            window.myPie = new Chart(ctx, config);
        };

        document.getElementById('randomizeData').addEventListener('click', function() {
            config.data.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });
            });

            window.myPie.update();
        });

        var colorNames = Object.keys(window.chartColors);
        document.getElementById('addDataset').addEventListener('click', function() {
            var newDataset = {
                backgroundColor: [],
                data: [],
                label: 'New dataset ' + config.data.datasets.length,
            };

            for (var index = 0; index < config.data.labels.length; ++index) {
                newDataset.data.push(randomScalingFactor());

                var colorName = colorNames[index % colorNames.length];
                var newColor = window.chartColors[colorName];
                newDataset.backgroundColor.push(newColor);
            }

            config.data.datasets.push(newDataset);
            window.myPie.update();
        });

        document.getElementById('removeDataset').addEventListener('click', function() {
            config.data.datasets.splice(0, 1);
            window.myPie.update();
        });
    </script>
    <script>
        var line = document.getElementById("myLine").getContext('2d');

        var myLine = new Chart(line, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($nama_produk); ?>,
                datasets: [{
                    label: 'Kasus Covid-19 Aktif',
                    data: <?php echo json_encode($jumlah_produk); ?>,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>

</body>

</html>