<?php
session_start();

// *  CEK ATURAN HAK AKSES JIKA BELUM LOGIN
if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");

  // *  CEK ATURAN HAK AKSES SEBAGAI ADMIN
} else if ($_SESSION["role"] != 'pegawai') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

// $judul = "Profile Pegawai";

include '../layout/header.php';
require_once '../../config.php';

$id = $_SESSION['id'];
$result = mysqli_query(
  $connection,
  "SELECT users.id_pegawai, users.username, users.status, users.role, pegawai.* 
FROM users 
JOIN pegawai ON  users.id_pegawai = pegawai.id
WHERE pegawai.id = $id"
);

?>

<?php while ($pegawai = mysqli_fetch_array($result)) : ?>
  <div class="page-body">
    <div class="container-xl">

      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-5">
          <div class="card">
            <div class="card-body">
              <center>
                <img style="border-radius: 100%;" src="<?= base_url('assets/img/foto_pegawai/' . $pegawai['foto']); ?>" alt="">
              </center>

              <table class="table mt-4">
                <tr>
                  <td>Nama</td>
                  <td>: <?= $pegawai['nama']; ?></td>
                </tr>

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
                  <td>No. Heandphone</td>
                  <td>: <?= $pegawai['no_handphone']; ?></td>
                </tr>

                <tr>
                  <td>Jabatan</td>
                  <td>: <?= $pegawai['jabatan']; ?></td>
                </tr>

                <tr>
                  <td>User Name</td>
                  <td>: <?= $pegawai['username']; ?></td>
                </tr>

                <tr>
                  <td>Role</td>
                  <td>: <?= $pegawai['role']; ?></td>
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
        <div class="col-md-4"></div>
      </div>

    </div>
  </div>

<?php endwhile; ?>

<?php include('../layout/footer.php'); ?>