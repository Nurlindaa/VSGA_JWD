<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS for table style -->
    <style>
        /* Alternate row background color */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        /* Even row background color */
        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        /* Table header text color and background */
        .table thead th {
            color: #ffffff;
            background-color: #007bff;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2>Daftar Pesanan</h2>
        <?php
        // Include the connection.php file to connect to the database
        include "koneksi.php";

        // Check if a delete request is submitted
        if(isset($_POST['delete_id'])) {
            $id_to_delete = $_POST['delete_id'];
            $sql = "DELETE FROM tabel_pemesanan WHERE id = $id_to_delete";
            if (mysqli_query($conn, $sql)) {
                echo '<div class="alert alert-success" role="alert">Data pemesanan berhasil dihapus.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Gagal menghapus data pemesanan: ' . mysqli_error($conn) . '</div>';
            }
        }

        // Retrieve order data from the database
        $sql = "SELECT * FROM tabel_pemesanan";
        $result = mysqli_query($conn, $sql);

        // Check if any data is found
        if (mysqli_num_rows($result) > 0) {
            // Start displaying the data
            echo '<table class="table">
                <thead>
                    <tr>
                        <th>Nama Pemesan</th>
                        <th>Nomor Telp/HP</th>
                        <th>Waktu Perjalanan</th>
                        <th>Jumlah Peserta</th>
                        <th>Pelayanan</th>
                        <th>Harga Paket</th>
                        <th>Total Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>';
            // Loop through each row of data and display it in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                    <td>' . $row['nama_pemesan'] . '</td>
                    <td>' . $row['no_telp'] . '</td>
                    <td>' . $row['waktu_perjalanan'] . '</td>
                    <td>' . $row['jumlah_peserta'] . '</td>
                    <td>' . $row['pelayanan'] . '</td>
                    <td>' . $row['harga_paket'] . '</td>
                    <td>' . $row['total_tagihan'] . '</td>
                    <td>
                        <a href="update.php?id=' . $row['id'] . '" class="btn btn-warning">Update</a>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="delete_id" value="' . $row['id'] . '">
                            <button type="submit" class="btn btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">Delete</button>
                        </form>
                    </td>
                </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "Tidak ada data pemesanan.";
        }

        // Be sure to free the result when you're done with it
        mysqli_free_result($result);

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
    
    <!-- Button to go back to the registration form -->
    <div class="container mt-3">
        <a href="form_pemesanan.php" class="btn btn-primary">Kembali ke Form Pemesanan</a>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
