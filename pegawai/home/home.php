<?php
session_start();

// *  CEK ATURAN HAK AKSES JIKA BELUM LOGIN
if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");

  // *  CEK ATURAN HAK AKSES SEBAGAI PEGAWAI
} else if ($_SESSION["role"] != 'pegawai') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Home";

include('../layout/header.php');
include_once("../../config.php");

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($connection, "SELECT * 
FROM lokasi
WHERE nama_lokasi = '$lokasi_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
  $latitude_kantor = $lokasi['latitude'];
  $longitude_kantor = $lokasi['longitude'];
  $radius = $lokasi['radius'];
  $zona_waktu = $lokasi['zona_waktu'];
  $jam_pulang = $lokasi['jam_pulang'];
}

// cek zona waktu 
if ($zona_waktu == 'WIB') {
  date_default_timezone_set('Asia/Jakarta');
} elseif ($zona_waktu == 'WITA') {
  date_default_timezone_set('Asia/Makassar');
} elseif ($zona_waktu == 'WIT') {
  date_default_timezone_set('Asia/Jayapura');
}
?>

<style>
  .parent_date {
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 25px;
    text-align: center;
    justify-content: center;
  }

  .parent_clock {
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 30px;
    text-align: center;
    font-weight: bold;
    justify-content: center;
  }
</style>

<!-- Page body -->
<div class="page-body">
  <div class="container-xl">
    <div class="row">

      <div class="col-md-2"></div>
      <div class="col-md-4">
        <div class="card text-center h-100">

          <div class="card-header">Presensi Masuk</div>
          <div class="card-body">

            <?php
            $id_pegawai = $_SESSION['id'];
            $tanggal_hari_ini = date('Y-m-d');

            $cek_presensi_masuk = mysqli_query(
              $connection,
              "SELECT * FROM presensi
              WHERE id_pegawai = '$id_pegawai'
              AND tanggal_masuk = '$tanggal_hari_ini'"
            );

            ?>

            <?php if (mysqli_num_rows($cek_presensi_masuk) === 0) { ?>

              <div class="parent_date">
                <div id="tanggal_masuk"></div>
                <div class="ms-2"></div>
                <div id="bulan_masuk"></div>
                <div class="ms-2"></div>
                <div id="tahun_masuk"></div>
              </div>

              <div class="parent_clock">
                <div id="jam_masuk"></div>
                <div>:</div>
                <div id="menit_masuk"></div>
                <div>:</div>
                <div id="detik_masuk"></div>
              </div>

              <form method="POST" action="<?= base_url('pegawai/presensi/presensi_masuk.php'); ?>">
                <input type="" name="latitude_pegawai" id="latitude_pegawai">
                <input type="" name="longitude_pegawai" id="longitude_pegawai">
                <input type="" value="<?= $latitude_kantor; ?>" name="latitude_kantor">
                <input type="" value="<?= $longitude_kantor; ?>" name="longitude_kantor">
                <input type="" value="<?= $radius; ?>" name="radius">
                <input type="" value="<?= $zona_waktu; ?>" name="zona_waktu">
                <input type="" value="<?= date('Y-m-d'); ?>" name="tanggal_masuk">
                <input type="" value="<?= date('H:i:s'); ?>" name="jam_masuk">
                <button type="submit" name="tombol_masuk" class="btn btn-primary mt-3">Masuk</button>
              </form>

            <?php  } else { ?>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check mt-3 mb-3" width="100" height="100" viewBox="0 0 24 24" stroke-width="3" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                <path d="M9 12l2 2l4 -4" />
              </svg>
              <h2>Anda Telah Absen Masuk</h2>
            <?php } ?>

          </div>

        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center h-100">
          <div class="card-header">Presensi Kelular</div>
          <div class="card-body">

            <?php
            $ambil_data_presensi = mysqli_query(
              $connection,
              "SELECT * FROM presensi
              WHERE id_pegawai = '$id_pegawai'
              AND tanggal_masuk = '$tanggal_hari_ini'"
            )
            ?>

            <?php $wakatu_sekarnag = date('H:i:s');

            if (strtotime($wakatu_sekarnag) <= strtotime($jam_pulang)) { ?>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle mt-3 mb-3" width="100" height="100" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
              </svg>
              <h2>Belum waktunya pulang</h2>

            <?php } else if (strtotime($wakatu_sekarnag) >= strtotime($jam_pulang) && mysqli_num_rows($ambil_data_presensi) == 0) { ?>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle mt-3 mb-3" width="100" height="100" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
              </svg>
              <h2>Silahkan Melakukan Absen </br> Terlebih Dahulu</h2>
            <?php } else { ?>

              <?php while ($cek_presensi_keluar = mysqli_fetch_array($ambil_data_presensi)) : ?>

                <?php if (($cek_presensi_keluar['tanggal_masuk']) && $cek_presensi_keluar['tanggal_keluar'] == '0000-00-00') { ?>

                  <div class="parent_date">
                    <div id="tanggal_keluar"></div>
                    <div class="ms-2"></div>
                    <div id="bulan_keluar"></div>
                    <div class="ms-2"></div>
                    <div id="tahun_keluar"></div>
                  </div>

                  <div class="parent_clock">
                    <div id="jam_keluar"></div>
                    <div>:</div>
                    <div id="menit_keluar"></div>
                    <div>:</div>
                    <div id="detik_keluar"></div>
                  </div>

                  <form method="POST" action="<?= base_url('pegawai/presensi/presensi_keluar.php'); ?>">

                    <input type="text" name="id" value="<?= $cek_presensi_keluar['id']; ?>">
                    <input type="" name="latitude_pegawai" id="latitude_pegawai">
                    <input type="" name="longitude_pegawai" id="longitude_pegawai">
                    <input type="" value="<?= $latitude_kantor; ?>" name="latitude_kantor">
                    <input type="" value="<?= $longitude_kantor; ?>" name="longitude_kantor">
                    <input type="" value="<?= $radius; ?>" name="radius">
                    <input type="" value="<?= $zona_waktu; ?>" name="zona_waktu">
                    <input type="" value="<?= date('Y-m-d'); ?>" name="tanggal_keluar">
                    <input type="" value="<?= date('H:i:s'); ?>" name="jam_keluar">

                    <button type="submit" name="tombol_keluar" class="btn btn-danger mt-3">Keluar</button>
                  </form>

                <?php } else { ?>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check mt-3 mb-3" width="100" height="100" viewBox="0 0 24 24" stroke-width="3" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                  </svg>
                  <h2>Anda Telah Absen Keluar</h2>
                <?php } ?>

              <?php endwhile; ?>

            <?php } ?>

          </div>
        </div>
      </div>
      <div class="col-md-2"></div>

    </div>
  </div>
</div>

<script>
  // set waktu presensi masuk
  window.setTimeout("waktuMasuk()", 1000);
  namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  function waktuMasuk() {
    const waktu = new Date();
    setTimeout("waktuMasuk()", 1000);
    document.getElementById("tanggal_masuk").innerHTML = waktu.getDate();
    document.getElementById("bulan_masuk").innerHTML = namaBulan[waktu.getMonth()];
    document.getElementById("tahun_masuk").innerHTML = waktu.getFullYear();
    document.getElementById("jam_masuk").innerHTML = waktu.getHours();
    document.getElementById("menit_masuk").innerHTML = waktu.getMinutes();
    document.getElementById("detik_masuk").innerHTML = waktu.getSeconds();
  }

  // set waktu presensi keluar
  window.setTimeout("waktuKeluar()", 1000);
  namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  function waktuKeluar() {
    const waktu = new Date();
    setTimeout("waktuKeluar()", 1000);
    document.getElementById("tanggal_keluar").innerHTML = waktu.getDate();
    document.getElementById("bulan_keluar").innerHTML = namaBulan[waktu.getMonth()];
    document.getElementById("tahun_keluar").innerHTML = waktu.getFullYear();
    document.getElementById("jam_keluar").innerHTML = waktu.getHours();
    document.getElementById("menit_keluar").innerHTML = waktu.getMinutes();
    document.getElementById("detik_keluar").innerHTML = waktu.getSeconds();
  }

  // cek lokasi redius pegawai
  getLocation();

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      alert("Browser Anda tidak Geolocation");
    }
  }

  function showPosition(position) {
    $('#latitude_pegawai').val(position.coords.latitude);
    $('#longitude_pegawai').val(position.coords.longitude);
  }
</script>

<?php include('../layout/footer.php'); ?>