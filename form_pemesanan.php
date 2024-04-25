<?php
// Include file koneksi.php
include "koneksi.php";

// Include file koneksi.php
include "koneksi.php";

// Periksa apakah data dari form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari formulir
    $nama_pemesan = $_POST["nama_pemesan"];
    $no_telp = $_POST["no_telp"];
    $waktu_perjalanan = $_POST["waktu_perjalanan"];
    $jumlah_peserta = $_POST["jumlah_peserta"];
    $pelayanan = implode(", ", $_POST["pelayanan"]); // Gabungkan nilai checkbox menjadi string dipisahkan koma
    $harga_paket = $_POST["harga_paket"];
    $total_tagihan = $_POST["total_tagihan"];

    // Query untuk menyimpan data ke dalam tabel database
    $query = "INSERT INTO tabel_pemesanan (nama_pemesan, no_telp, waktu_perjalanan, jumlah_peserta, pelayanan, harga_paket, total_tagihan)
              VALUES ('$nama_pemesan', '$no_telp', '$waktu_perjalanan', '$jumlah_peserta', '$pelayanan', '$harga_paket', '$total_tagihan')";

    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        // Jika data berhasil disimpan, redirect ke halaman lain atau tampilkan pesan sukses
        echo "<script>alert('Data berhasil disimpan');</script>";
    } else {
        // Jika terjadi kesalahan saat menyimpan data, tampilkan pesan error
        echo "<script>alert('Terjadi kesalahan saat menyimpan data');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Paket Wisata</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2>Form Pemesanan Paket Wisata</h2>
        <form id="formPemesanan" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <div class="form-group">
                <label for="nama">Nama Pemesan:</label>
                <input type="text" class="form-control" id="nama" name="nama_pemesan" required>
            </div>

            <!-- Nomor Telp/HP -->
            <div class="form-group">
                <label for="telp">Nomor Telp/HP:</label>
                <input type="tel" class="form-control" id="telp" name="no_telp" required>
            </div>

            <!-- Waktu Pelaksanaan Perjalanan -->
            <div class="form-group">
                <label for="waktu">Waktu Pelaksanaan Perjalanan:</label>
                <input type="date" class="form-control" id="waktu" name="waktu_perjalanan" required>
            </div>

            <!-- Jumlah Peserta -->
            <div class="form-group">
                <label for="peserta">Jumlah Peserta:</label>
                <input type="number" class="form-control" id="peserta" name="jumlah_peserta" required>
            </div>

            <!-- Pelayanan Paket Perjalanan -->
            <div class="form-group">
                <label>Pelayanan Paket Perjalanan:</label><br>
                <input type="checkbox" id="penginapan" name="pelayanan[]" value="penginapan">
                <label for="penginapan">Penginapan</label><br>
                <input type="checkbox" id="transportasi" name="pelayanan[]" value="transportasi">
                <label for="transportasi">Transportasi</label><br>
                <input type="checkbox" id="makanan" name="pelayanan[]" value="makanan">
                <label for="makanan">Makanan</label>
            </div>

            <!-- Harga Paket Perjalanan -->
            <div class="form-group">
                <label for="harga">Harga Paket Perjalanan:</label>
                <input type="number" class="form-control" id="harga" name="harga_paket" value="1500000" readonly>
            </div>

            <!-- Jumlah Tagihan -->
            <div class="form-group">
                <label for="tagihan">Jumlah Tagihan:</label>
                <input type="number" class="form-control" id="tagihan" name="total_tagihan" readonly>
                <div id="totalTagihan"></div>
            </div>

            <!-- Tombol Simpan, Lihat Pesanan, dan Batal -->
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="daftar_pemesanan.php" class="btn btn-info">Lihat Pesanan</a>
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">Batal</button>
        </form>
    </div>

    <!-- Modal Pop-up -->
    <div class="modal" tabindex="-1" role="dialog" id="konfirmasiModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pemesanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Nama: <span id="modalNama"></span></p>
                    <p>Jumlah Peserta: <span id="modalPeserta"></span></p>
                    <p>Waktu Perjalanan: <span id="modalWaktu"></span></p>
                    <p>Layanan Paket: <span id="modalLayanan"></span></p>
                    <p>Harga Paket: <span id="modalHarga"></span></p>
                    <p>Jumlah Tagihan: <span id="modalTagihan"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Konfirmasi</button>
                    <button type="button" class="btn btn-danger" onclick="deletePemesanan()">Delete</button>
                    <button type="button" class="btn btn-success" onclick="editPemesanan()">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript untuk Pop-up -->
    <script>
        // Fungsi untuk menampilkan modal konfirmasi
        $('#formPemesanan').submit(function(event) {
            event.preventDefault(); // Mencegah form dari submit biasa
            // Mengisi modal dengan data dari form
            $('#modalNama').text($('#nama').val());
            $('#modalPeserta').text($('#peserta').val());
            $('#modalWaktu').text($('#waktu').val());
            // Tambahkan logika untuk mengisi layanan paket, harga paket, dan jumlah tagihan
            var layananPaket = [];
            $('input[name="pelayanan[]"]:checked').each(function() {
                layananPaket.push($(this).val());
            });
            $('#modalLayanan').text(layananPaket.join(', '));
            $('#modalHarga').text($('#harga').val());
            // Tambahkan logika untuk menghitung jumlah tagihan
            var jumlahTagihan = calculateTagihan();
            $('#modalTagihan').text(jumlahTagihan);
            $('#konfirmasiModal').modal('show'); // Menampilkan modal
        });

        // Tambahkan event listener pada input terakhir dalam formulir
        $('#tagihan').prevAll('input, select, textarea').last().on('blur', function() {
            // Hitung total tagihan
            var totalTagihan = calculateTagihan();
            // Tetapkan nilai total tagihan ke dalam input total tagihan
            $('#tagihan').val(totalTagihan);
        });

        // Fungsi untuk menampilkan total tagihan dan menampilkan hasilnya
        function calculateTagihan() {
            var jumlahPeserta = parseInt($('#peserta').val());
            var hargaPaket = parseInt($('#harga').val());
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

        $('input[name="pelayanan[]"]').change(function() {
            var jumlahPeserta = $('#peserta').val();
            var hargaPaket = $('#harga').val();
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
            $('#tagihan').val(totalTagihan);
        });

        // Fungsi untuk submit form setelah konfirmasi
        function submitForm() {
            document.getElementById('formPemesanan').submit();
        }

        function editPemesanan() {
            // Ambil data dari modal
            var nama = $('#modalNama').text();
            var peserta = $('#modalPeserta').text();
            var waktu = $('#modalWaktu').text();
            var layanan = $('#modalLayanan').text();
            var harga = $('#modalHarga').text();
            var tagihan = $('#modalTagihan').text();

            // Mengatur nilai-nilai input di form input
            $('#nama').val(nama);
            $('#peserta').val(peserta);
            $('#waktu').val(waktu);
            // Mengatur nilai checkbox berdasarkan layanan yang dipilih
            var layananArray = layanan.split(', ');
            $('input[name="pelayanan[]"]').each(function() {
                $(this).prop('checked', layananArray.includes($(this).val()));
            });
            $('#harga').val(harga);
            $('#tagihan').val(tagihan);

            // Sembunyikan modal
            $('#konfirmasiModal').modal('hide');

            // Tampilkan form input
            $('#formPemesanan').show();
        }

        // Fungsi untuk menghapus data pemesanan dengan konfirmasi
        function deletePemesanan() {
            // Tampilkan dialog konfirmasi
            var confirmation = confirm("Apakah Anda yakin ingin menghapus data pemesanan?");

            // Jika pengguna menekan tombol OK (ya), hapus data
            if (confirmation) {
                // Implementasi penghapusan data dari database
                // Contoh: Anda dapat menggunakan AJAX untuk mengirim permintaan penghapusan ke server
                // dan kemudian menanggapi permintaan tersebut dengan menampilkan pesan sukses atau gagal.
                // Di sini saya hanya menampilkan pesan sederhana untuk tujuan demonstrasi.
                alert("Data pemesanan telah dihapus.");
                // Kemudian Anda bisa menutup modal atau melakukan tindakan lainnya
                $('#konfirmasiModal').modal('hide');
            }
            // Jika pengguna menekan tombol Cancel (tidak), tidak lakukan apa-apa
            else {
                // Tidak melakukan apa-apa atau mungkin menutup dialog konfirmasi
            }
        }
    </script>

</body>

</html>
