<?php
session_start();
ob_start();

// *  CEK ATURAN HAK AKSES JIKA BELUM LOGIN
if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");

  // *  CEK ATURAN HAK AKSES SEBAGAI ADMIN
} else if ($_SESSION["role"] != 'admin') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Tambah Lokasi Presensi";

include '../layout/header.php';
require_once '../../config.php';

if (isset($_POST['submit'])) {
  $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
  $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
  $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi']);
  $latitude = htmlspecialchars($_POST['latitude']);
  $longitude = htmlspecialchars($_POST['longitude']);
  $radius = htmlspecialchars($_POST['radius']);
  $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
  $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
  $jam_pulang = htmlspecialchars($_POST['jam_pulang']);

  // * validasi data kosong
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($nama_lokasi)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Nama lokasi harus diisi";
    }
    if (empty($alamat_lokasi)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Alamat lokasi harus diisi";
    }
    if (empty($tipe_lokasi)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Tipe lokasi harus diisi";
    }
    if (empty($latitude)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Latitude harus diisi";
    }
    if (empty($longitude)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Longitude harus diisi";
    }
    if (empty($radius)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Radius lokasi harus diisi";
    }
    if (empty($zona_waktu)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Zona Waktu harus diisi";
    }
    if (empty($jam_masuk)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Jam Masuk harus diisi";
    }
    if (empty($jam_pulang)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Jam Pulang harus diisi";
    }


    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
      $result = mysqli_query(
        $connection,
        "INSERT INTO lokasi(nama_lokasi, alamat_lokasi, tipe_lokasi, latitude, longitude, radius, zona_waktu, jam_masuk, jam_pulang)
  VALUES('$nama_lokasi', '$alamat_lokasi', '$tipe_lokasi', '$latitude', '$longitude', '$radius', '$zona_waktu', '$jam_masuk', '$jam_pulang')"
      );

      $_SESSION['berhasil'] = 'Data Berhasil Disimpan';
      header("Location: lokasi.php");
      exit;
    }
  }
}

?>


<div class="page-body">
  <div class="container-xl">

    <div class="card col-md-6">
      <div class="card-body">
        <!-- <form action="#"> -->
        <form action="<?= base_url('admin/data_lokasi/tambah.php'); ?>" method="POST">
          <div class="mb-3">
            <label for="">Nama Lokasi</label>
            <input type="text" class="form-control" name="nama_lokasi" value="<?php if (isset($_POST['nama_lokasi'])) echo $_POST['nama_lokasi']; ?>">
          </div>

          <div class="mb-3">
            <label for="">Alamat Lokasi</label>
            <input type="text" class="form-control" name="alamat_lokasi" value="<?php if (isset($_POST['alamat_lokasi'])) echo $_POST['alamat_lokasi']; ?>">
          </div>

          <div class="mb-3">
            <label for="">Tipe Lokasi</label>
            <select name="tipe_lokasi" class="form-control">
              <option value="">- Pilih Tipe Lokasi -</option>
              <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'Kantor Pusat') {
                        echo 'selected';
                      }; ?> value="Kantor Pusat">Kantor Pusat</option>
              <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'Kantor Cabang') {
                        echo 'selected';
                      }; ?> value="Kantor Cabang">Kantor Cabang</option>
              <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'WFH') {
                        echo 'selected';
                      }; ?> value="WFH">WFH</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="">Latitude</label>
            <input type="text" class="form-control" name="latitude" value="<?php if (isset($_POST['latitude'])) echo $_POST['latitude']; ?>">
          </div>

          <div class="mb-3">
            <label for="">Longitude</label>
            <input type="text" class="form-control" name="longitude" value="<?php if (isset($_POST['latitude'])) echo $_POST['latitude']; ?>">
          </div>

          <div class="mb-3">
            <label for="">Radius</label>
            <input type="number" class="form-control" name="radius" value="<?php if (isset($_POST['radius'])) echo $_POST['radius']; ?>">
          </div>

          <div class="mb-3">
            <label for="">Zona Waktu</label>
            <select name="zona_waktu" class="form-control">
              <option value="">- Pilih Zona Waktu -</option>
              <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WIB') {
                        echo 'selected';
                      }; ?> value="WIB">WIB</option>
              <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WITA') {
                        echo 'selected';
                      }; ?> value="WITA">WITA</option>
              <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WIT') {
                        echo 'selected';
                      }; ?> value="WIT">WIT</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="">Jam Masuk</label>
            <input type="time" class="form-control" name="jam_masuk" value="<?php if (isset($_POST['jam_masuk'])) echo $_POST['jam_masuk']; ?>">
          </div>

          <div class="mb-3">
            <label for="">Jam Pulang</label>
            <input type="time" class="form-control" name="jam_pulang" value="<?php if (isset($_POST['jam_pulang'])) echo $_POST['jam_pulang']; ?>">
          </div>

          <button type="submit" class="btn btn-primary" name="submit">Simpan</button>

        </form>
      </div>
    </div>

  </div>
</div>

<?php include('../layout/footer.php'); ?>