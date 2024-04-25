<?php
// Include file koneksi.php untuk menghubungkan ke database
include "koneksi.php";

// Periksa apakah data yang diperlukan telah diterima dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Tangkap data yang diterima dari formulir
    $id = $_POST['id'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $no_telp = $_POST['no_telp'];
    $waktu_perjalanan = $_POST['waktu_perjalanan'];
    $jumlah_peserta = $_POST['jumlah_peserta'];
    $pelayanan = implode(", ", $_POST['pelayanan']); // Menggabungkan pelayanan menjadi satu string dipisahkan oleh koma
    $harga_paket = $_POST['harga_paket'];
    $total_tagihan = $_POST['total_tagihan'];

    // Query SQL untuk update data reservasi
    $sql = "UPDATE tabel_pemesanan SET nama_pemesan=?, no_telp=?, waktu_perjalanan=?, jumlah_peserta=?, pelayanan=?, harga_paket=?, total_tagihan=? WHERE id=?";

    // Persiapkan statement SQL
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameter-parameter ke statement
    mysqli_stmt_bind_param($stmt, 'sssisdsi', $nama_pemesan, $no_telp, $waktu_perjalanan, $jumlah_peserta, $pelayanan, $harga_paket, $total_tagihan, $id);

    // Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Jika update berhasil, arahkan kembali ke halaman daftar_pemesanan.php
        header("location: daftar_pemesanan.php");
        exit();
    } else {
        // Jika update gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}

// Periksa apakah nomor reservasi sudah diset atau tidak
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data reservasi berdasarkan id
    $sql = "SELECT * FROM tabel_pemesanan WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Jika data ditemukan, tampilkan formulir untuk mengedit
    if ($row) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>edit pemesanan</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Tambahkan event listener pada input terakhir dalam formulir
                    $('#total_tagihan').prevAll('input, select, textarea').last().on('blur', function() {
                        // Hitung total tagihan
                        var totalTagihan = calculateTagihan();
                        // Tetapkan nilai total tagihan ke dalam input total tagihan
                        $('#total_tagihan').val(totalTagihan);
                    });

                    // Fungsi untuk menampilkan total tagihan dan menampilkan hasilnya
                    function calculateTagihan() {
                        var jumlahPeserta = parseInt($('#jumlah_peserta').val());
                        var hargaPaket = parseInt($('#harga_paket').val());
                        var totalTagihan = jumlahPeserta * hargaPaket;
                        if ($('#penginapan').is(':checked')) {
                            totalTagihan += 1500000;
                        }
                        if ($('#transportasi').is(':checked')) {
                            totalTagihan += 500000;
                        }
                        if ($('#makanan').is(':checked')) {
                            totalTagihan += 1000000;
                        }
                        return totalTagihan;
                    }

                    // Jalankan fungsi calculateTagihan saat ada perubahan pada checkbox pelayanan
                    $('input[name="pelayanan[]"]').change(function() {
                        var totalTagihan = calculateTagihan();
                        $('#total_tagihan').val(totalTagihan);
                    });
                });
            </script>
        </head>

        <body>
            <div class="container">
                <h2>edit pemesanan</h2>
                <form action="update.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label for="nama_pemesan">Nama Pemesan:</label>
                        <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" value="<?php echo $row['nama_pemesan']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="no_telp">Nomor Telepon/HP:</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $row['no_telp']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="waktu_perjalanan">Waktu Perjalanan:</label>
                        <input type="text" class="form-control" id="waktu_perjalanan" name="waktu_perjalanan" value="<?php echo $row['waktu_perjalanan']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_peserta">Jumlah Peserta:</label>
                        <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" value="<?php echo $row['jumlah_peserta']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Pelayanan Paket Perjalanan:</label><br>
                        <input type="checkbox" id="penginapan" name="pelayanan[]" value="penginapan">
                        <label for="penginapan">Penginapan</label><br>
                        <input type="checkbox" id="transportasi" name="pelayanan[]" value="transportasi">
                        <label for="transportasi">Transportasi</label><br>
                        <input type="checkbox" id="makanan" name="pelayanan[]" value="makanan">
                        <label for="makanan">Makanan</label>
                    </div>
                    <div class="form-group">
                        <label for="harga_paket">Harga Paket:</label>
                        <input type="text" class="form-control" id="harga_paket" name="harga_paket" value="<?php echo $row['harga_paket']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="total_tagihan">Total Tagihan:</label>
                        <input type="text" class="form-control" id="total_tagihan" name="total_tagihan" value="<?php echo $row['total_tagihan']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button> <a href="daftar_pemesanan.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>

            <!-- Tambahkan script JavaScript Bootstrap (jika diperlukan) -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>
<?php
    } else {
        echo "<p>id tidak ditemukan.</p>";
    }
} else {
    echo "<p>id tidak diberikan.</p>";
}


// Tutup koneksi database
mysqli_close($conn);
?>