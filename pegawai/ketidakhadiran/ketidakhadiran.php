<?php
ob_start();
session_start();
// *  CEK ATURAN HAK AKSES JIKA BELUM LOGIN
if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");

  // *  CEK ATURAN HAK AKSES SEBAGAI PEGAWAI
} else if ($_SESSION["role"] != 'pegawai') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Ketidakhadiran";

include('../layout/header.php');
include_once("../../config.php");

$id = $_SESSION['id'];
$result = mysqli_query(
  $connection,
  "SELECT * FROM ketidakhadiran 
  WHERE id_pegawai = '$id'
  ORDER BY id DESC"
);
?>

<div class="page-body">
  <div class="container-xl">

    <a href="<?= base_url('pegawai/ketidakhadiran/pengajuan_ketidakhadiran.php'); ?>" class="btn btn-primary">Ketidakhadiran</a>
    <table class="table table-bordered mt-3">
      <tr class="text-center">
        <th>No.</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Deskripsi</th>
        <th>File</th>
        <th>Status Pengajuan</th>
        <th>Aksi</th>
      </tr>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <tr>
          <td colspan="7">Data ketidakhadiran masih kosong</td>
        </tr>
      <?php } else { ?>
        <?php $no = 1;
        while ($data = mysqli_fetch_array($result)) : ?>

          <tr class="text-center">
            <td><?= $no++; ?></td>
            <td><?= date('d F Y', strtotime($data['tanggal'])); ?></td>
            <td><?= $data['keterangan']; ?></td>
            <td class="text-start"><?= $data['deskripsi']; ?></td>
            <td>
              <a href="<?= base_url('assets/file_ketidakhadiran/' . $data['file']) ?>" target="_blank" class="badge badge-pill bg-primary">Download</a>
            </td>
            <td><?= $data['status_pengajuan']; ?></td>
            <td>
              <a href="edit.php?id=<?= $data['id']; ?>" class="badge badge-fill bg-success">Update</a>
              <a href="hapus.php?id=<?= $data['id']; ?>" class="badge badge-fill bg-danger tombol-hapus">Delete</a>
            </td>
          </tr>

        <?php endwhile; ?>
      <?php } ?>
    </table>

  </div>
</div>

<?php include('../layout/footer.php'); ?>