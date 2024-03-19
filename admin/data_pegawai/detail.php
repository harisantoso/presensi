<?php
session_start();

// *  CEK ATURAN HAK AKSES JIKA BELUM LOGIN
if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");

  // *  CEK ATURAN HAK AKSES SEBAGAI ADMIN
} else if ($_SESSION["role"] != 'admin') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Detail Data Pegawai";

include '../layout/header.php';
require_once '../../config.php';

$id = $_GET['id'];
$result = mysqli_query(
  $connection,
  "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai. * 
FROM users 
JOIN pegawai ON  users.id_pegawai = pegawai.id WHERE pegawai.id = $id"
);


?>

<?php
while ($pegawai = mysqli_fetch_array($result)) : ?>

  <div class="page-body">
    <div class="container-xl">

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">

              <table class="table">
                <tr>
                  <td>Nama</td>
                  <td>: <?= $pegawai['nama']; ?></td>
                </tr>

                <tr>
                  <td>Jenis Kelamin</td>
                  <td>: <?= $pegawai['jenis_kelamin']; ?></td>
                </tr>

                <tr>
                  <td>Alamat</td>
                  <td>: <?= $pegawai['alamat']; ?></td>
                </tr>

                <tr>
                  <td>No HandPhone</td>
                  <td>: <?= $pegawai['no_handphone']; ?></td>
                </tr>

                <tr>
                  <td>Jabatan</td>
                  <td>: <?= $pegawai['jabatan']; ?></td>
                </tr>

                <tr>
                  <td>Username</td>
                  <td>: <?= $pegawai['username']; ?></td>
                </tr>

                <tr>
                  <td>Lokasi Presensi</td>
                  <td>: <?= $pegawai['lokasi_presensi']; ?></td>
                </tr>

                <tr>
                  <td>Status</td>
                  <td>: <?= $pegawai['status']; ?></td>
                </tr>
              </table>

            </div>
          </div>
        </div>

        <div class="col-md-6">

          <img style="width: 350px; border-radius: 10px" src="<?= base_url('assets/img/foto_pegawai/' . $pegawai['foto']); ?>" alt="">

        </div>
      </div>

      <a href="<?= base_url('admin/data_pegawai/pegawai.php'); ?>" class="btn btn-primary mt-3">

        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M5 12l14 0" />
          <path d="M5 12l4 4" />
          <path d="M5 12l4 -4" />
        </svg>Kembali ke Data Pegawai</a>
    </div>
  </div>

<?php endwhile; ?>

<?php include('../layout/footer.php'); ?>