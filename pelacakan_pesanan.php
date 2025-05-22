<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "cuci_sepatu";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id_customer = isset($_GET['id_customer']) ? trim($_GET['id_customer']) : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pelacakan Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
        }

        button {
            padding: 8px 16px;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            margin-top: 20px;
            width: 100%;
            max-width: 900px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .diproses {
            background-color: yellow;
        }

        .selesai {
            background-color: lightgreen;
        }

        .dibatalkan {
            background-color: lightcoral;
        }
    </style>
</head>

<body>
    <h2>Pelacakan Pesanan</h2>

    <form method="GET" action="">
        <label for="id_customer">Masukkan ID Customer:</label>
        <input type="text" name="id_customer" id="id_customer" required value="<?= htmlspecialchars($id_customer) ?>">
        <button type="submit">Cari</button>
    </form>

    <?php
    if ($id_customer != '') {
        $stmt = $conn->prepare("SELECT id_pesanan, id_customer, tanggal_pesanan, status_pesanan, total_harga FROM pesanan WHERE id_customer LIKE ?");
        $like_id = "%" . $id_customer . "%";
        $stmt->bind_param("s", $like_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h3>Pesanan untuk pelanggan: <em>" . htmlspecialchars($id_customer) . "</em></h3>";
            echo "<table>";
            echo "<tr>
                    <th>Id Pesanan</th>
                    <th>Id Pelanggan</th>
                    <th>Tanggal Pesanan</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                  </tr>";

            while ($row = $result->fetch_assoc()) {
                $class = "";
                $status = strtolower($row['status_pesanan']);
                if ($status === 'diproses') {
                    $class = 'diproses';
                } elseif ($status === 'selesai') {
                    $class = 'selesai';
                } elseif ($status === 'dibatalkan') {
                    $class = 'dibatalkan';
                }

                echo "<tr class='{$class}'>";
                echo "<td>" . htmlspecialchars($row['id_pesanan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['id_customer']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tanggal_pesanan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status_pesanan']) . "</td>";
                echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Tidak ada pesanan untuk pelanggan dengan ID: <strong>" . htmlspecialchars($id_customer) . "</strong></p>";
        }

        $stmt->close();
    } else {
        echo "<p>Masukkan ID pelanggan untuk mencari pesanan.</p>";
    }

    $conn->close();
    ?>
</body>

</html>
