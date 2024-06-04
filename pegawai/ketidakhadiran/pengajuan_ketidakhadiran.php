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

$judul = "Pengajuan Ketidakhadiran";

include('../layout/header.php');
include_once("../../config.php");

if (isset($_POST['submit'])) {
  $id = $_POST['id_pegawai'];
  $keterangan = $_POST['keterangan'];
  $deskripsi = $_POST['deskripsi'];
  $tanggal = $_POST['tanggal'];
  $status_pengajuan = 'PENDING';

  if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $nama_file = $file['name'];
    $file_tmp = $file['tmp_name'];
    $ukuran_file = $file['size'];
    $file_direktori = "../../assets/file_ketidakhadiran/" . $nama_file;

    $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $ekstensi_allow = ["jpg", "jpeg", "png"];
    $max_file = 2 * 1024 * 1024;

    move_uploaded_file($file_tmp, $file_direktori);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($keterangan)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Keterangan Wajib diisi";
    }

    if (empty($deskripsi)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Deskripsi Wajib diisi";
    }

    if (empty($tanggal)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Tanggal Wajib diisi";
    }

    // * validasi exktensi file lampiran surat keterangan 
    if (!in_array(strtolower($ambil_ekstensi), $ekstensi_allow)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Hanya file JPG,JPEG, dan PNG yg digunakan.";
    }

    // * validasi file size  
    if ($ukuran_file > $max_file) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Ukuran file hanya 2MB.";
    }

    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
      $result = mysqli_query(
        $connection,
        "INSERT INTO ketidakhadiran(id_pegawai, keterangan, tanggal, deskripsi, file, status_pengajuan)
      VALUES('$id', '$keterangan', '$tanggal', '$deskripsi', '$nama_file', '$status_pengajuan')"
      );

      $_SESSION['berhasil'] = 'Data Berhasil Disimpan';
      header("Location: ketidakhadiran.php");
      exit;
    }
  }
}

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

    <div class="card col-md-6">
      <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" value="<?= $_SESSION['id']; ?>" name="id_pegawai">

          <div class="mb-3">
            <label for="">Keterangan</label>
            <select name="keterangan" class="form-control">
              <option value="">- Pilih Keterangan -</option>
              <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'cuti') {
                        echo 'selected';
                      }; ?> value="cuti">Cuti</option>

              <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'izin') {
                        echo 'selected';
                      }; ?> value="izin">Izin</option>

              <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'sakit') {
                        echo 'selected';
                      }; ?> value="sakit">Sakit</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" cols="50" rows="5"></textarea>
          </div>

          <div class="mb-3">
            <label for="">Tanggal</label>
            <input type="date" class="form-control" name="tanggal">
          </div>

          <div class="mb-3">
            <label for="">Surat Keterangan</label>
            <input type="file" class="form-control" name="file">
          </div>

          <button type="submit" class="btn btn-primary" name="submit">Ajukan</button>
        </form>
      </div>
    </div>

  </div>
</div>

<?php include('../layout/footer.php'); ?>