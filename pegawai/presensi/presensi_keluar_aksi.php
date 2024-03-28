<?php
ob_start(); // Cannot modify header information - headers already sent by
session_start();

// *  CEK ATURAN HAK AKSES JIKA BELUM LOGIN
if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");
  // *  CEK ATURAN HAK AKSES SEBAGAI PEGAWAI
} else if ($_SESSION["role"] != 'pegawai') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

include_once("../../config.php");

$file_foto = $_POST['photo'];
$id_presensi = $_POST['id'];
$tanggal_keluar = $_POST['tanggal_keluar'];
$jam_keluar = $_POST['jam_keluar'];

$foto = $file_foto;
$foto = str_replace('data:image/jpeg;base64,', '', $foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);
$nama_file = 'foto/' . date('Y.m.d') . 'keluar' . date('H.i.s') . '.png';
$file = date('Y.m.d') . 'keluar' . date('H.i.s') . '.png';
file_put_contents($nama_file, $data);

$result = mysqli_query(
  $connection,
  "UPDATE presensi
  SET tanggal_keluar = '$tanggal_keluar', jam_keluar = '$jam_keluar', foto_keluar = '$file'
  WHERE id = $id_presensi"
);

if ($result) {
  $_SESSION['berhasil'] = "Presensi Keluar Berhasil";
} else {
  $_SESSION['gagal'] = "Presensi Keluar Gagal";
}
