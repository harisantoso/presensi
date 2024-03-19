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

$judul = "Edit Data Pegawai";

include '../layout/header.php';
require_once '../../config.php';

if (isset($_POST['edit'])) {

  $id = $_POST['id'];
  $nama = htmlspecialchars($_POST['nama']);
  $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
  $alamat = htmlspecialchars($_POST['alamat']);
  $no_handphone = htmlspecialchars($_POST['no_handphone']);
  $jabatan = htmlspecialchars($_POST['jabatan']);
  $status = htmlspecialchars($_POST['status']);
  $username = htmlspecialchars($_POST['username']);
  $role = htmlspecialchars($_POST['role']);
  $lokasi_presensi = htmlspecialchars($_POST['lokasi_presensi']);

  // * logika password jika user tidak mengisi password maka gunakan 
  // * pwd yg lama
  if (empty($_POST['password'])) {
    $password = $_POST['password_lama'];
  } else {
    // $password = htmlspecialchars($_POST['password']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  }

  // * update foto 
  // * masih terupload walalupun tipe & sizenya tidak sesuai
  if ($_FILES['foto_baru']['error'] === 4) {
    $nama_file = $_POST['foto_lama'];
  } else {
    if (isset($_FILES['foto_baru'])) {
      $file = $_FILES['foto_baru'];
      $nama_file = $file['name'];
      $file_tmp = $file['tmp_name'];
      $ukuran_file = $file['size'];
      $file_direktori = "../../assets/img/foto_pegawai/" . $nama_file;

      $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
      $ekstensi_allow = ["jpg", "jpeg", "png"];
      $max_file = 2 * 1024 * 1024;

      move_uploaded_file($file_tmp, $file_direktori);
    }
  }

  // * validasi data kosong
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($nama)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Nama harus diisi";
    }
    if (empty($jenis_kelamin)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Jenis Kelamin harus diisi";
    }
    if (empty($alamat)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Alamat harus diisi";
    }
    if (empty($no_handphone)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>No Handphone harus diisi";
    }
    if (empty($jabatan)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Jabatan harus diisi";
    }
    if (empty($status)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Status harus diisi";
    }
    if (empty($username)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Username harus diisi";
    }

    // * validasi password
    if (empty($password)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Password kosong";
    }

    // * validasi password tidak singgkron
    if ($_POST['password'] != $_POST['ulang_password']) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Password Tidak cocok";
    }

    if (empty($role)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Role harus diisi";
    }
    if (empty($lokasi_presensi)) {
      $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Lokasi Presensi harus diisi";
    }

    // * jika ada updatean foto baru
    if ($_FILES['foto_baru']['error'] != 4) {
      // * validasi exktensi file poto 
      if (!in_array(strtolower($ambil_ekstensi), $ekstensi_allow)) {
        $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Hanya file JPG,JPEG, dan PNG yg digunakan.";
      }
      // * validasi file size poto 
      if ($ukuran_file > $max_file) {
        $pesan_kesalahan[] = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-check'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l5 5l10 -10' /></svg>Ukuran file hanya 2MB.";
      }
    }

    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
      $pegawai = mysqli_query(
        $connection,
        "UPDATE pegawai SET
          nama = '$nama', 
          jenis_kelamin = '$jenis_kelamin', 
          alamat = '$alamat', 
          no_handphone = '$no_handphone', 
          jabatan = '$jabatan', 
          lokasi_presensi = '$lokasi_presensi', 
          foto = '$nama_file'
        WHERE id = '$id'"
      );

      $user = mysqli_query(
        $connection,
        "UPDATE users SET
        username = '$username',
        password = '$password',
        status = '$status',
        role = '$role'
      WHERE id = '$id'"
      );

      $_SESSION['berhasil'] = 'Data Berhasil Disimpan';
      header("Location: pegawai.php");
      exit;
    }
  }
}


$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

$result = mysqli_query(
  $connection,
  "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai. * 
FROM users 
JOIN pegawai ON  users.id_pegawai = pegawai.id WHERE pegawai.id = $id"
);

while ($pegawai = mysqli_fetch_array($result)) {
  $nama = $pegawai['nama'];
  $jenis_kelamin = $pegawai['jenis_kelamin'];
  $alamat = $pegawai['alamat'];
  $no_handphone = $pegawai['no_handphone'];
  $jabatan = $pegawai['jabatan'];
  $status = $pegawai['status'];
  $username = $pegawai['username'];
  $password = $pegawai['password'];
  $role = $pegawai['role'];
  $lokasi_presensi = $pegawai['lokasi_presensi'];
  $foto = $pegawai['foto'];
}

?>

<div class="page-body">
  <div class="container-xl">

    <form action="<?= base_url('admin/data_pegawai/edit.php'); ?>" method="POST" enctype="multipart/form-data">
      <div class="row">

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">

              <div class="mb-3">
                <label for="">Nama</label>
                <input type="text" class="form-control" name="nama" value="<?= $nama; ?>">
              </div>

              <div class="mb-3">
                <label for="">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control">
                  <option value="">- Pilih Jenis Kelamin -</option>
                  <option <?php if ($jenis_kelamin == 'Laki-laki') {
                            echo 'selected';
                          }; ?> value="Laki-laki">Laki-laki</option>
                  <option <?php if ($jenis_kelamin == 'Perempuan') {
                            echo 'selected';
                          }; ?> value="Perempuan">Perempuan</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="">Alamat</label>
                <input type="text" class="form-control" name="alamat" value="<?= $alamat; ?>">
              </div>

              <div class="mb-3">
                <label for="">No Handphone</label>
                <input type="text" class="form-control" name="no_handphone" value="<?= $no_handphone; ?>">
              </div>

              <div class="mb-3">
                <label for="">Jabatan</label>
                <select name="jabatan" class="form-control">
                  <option value="">- Pilih Jabatan -</option>

                  <?php
                  // * mengambil queri data jabatan 
                  $ambil_jabatan = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY jabatan ASC");

                  while ($row = mysqli_fetch_assoc($ambil_jabatan)) {
                    $nama_jabatan = $row['jabatan'];

                    if ($jabatan == $nama_jabatan) {
                      echo "<option value='$nama_jabatan' selected='selected'>$nama_jabatan</option>";
                    } else {
                      echo "<option value='$nama_jabatan'>$nama_jabatan</option>";
                    }
                  }
                  ?>

                </select>
              </div>

              <div class="mb-3">
                <label for="">Status</label>
                <select name="status" class="form-control">
                  <option value="">- Pilih Status -</option>
                  <option <?php if ($status == 'Aktif') {
                            echo 'selected';
                          }; ?> value="Aktif">Aktif</option>
                  <option <?php if ($status == 'Tidak Aktif') {
                            echo 'selected';
                          }; ?> value="Tidak Aktif">Tidak Aktif</option>
                </select>
              </div>

            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">

              <div class="mb-3">
                <label for="">Username</label>
                <input type="text" class="form-control" name="username" value="<?= $username; ?>">
              </div>

              <div class="mb-3">
                <label for="">Password</label>
                <input type="text" value="<?= $password; ?>" name="password_lama">
                <input type="password" class="form-control" name="password">
              </div>

              <div class="mb-3">
                <label for="">Ulangi Password</label>
                <input type="password" class="form-control" name="ulang_password">
              </div>

              <div class="mb-3">
                <label for="">Role</label>
                <select name="role" class="form-control">
                  <option value="">- Pilih Role -</option>
                  <option <?php if ($role == 'admin') {
                            echo 'selected';
                          }; ?> value="admin">Admin</option>
                  <option <?php if ($role == 'pegawai') {
                            echo 'selected';
                          }; ?> value="pegawai">Tidak Aktif</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="">Lokasi Presensi</label>
                <select name="lokasi_presensi" class="form-control">
                  <option value="">- Pilih Lokasi Presensi -</option>

                  <?php
                  // * mengambil queri data lokasi presensi 
                  $ambil_lok_presensi = mysqli_query($connection, "SELECT * FROM lokasi ORDER BY nama_lokasi ASC");

                  while ($lokasi = mysqli_fetch_assoc($ambil_lok_presensi)) {
                    $nama_lokasi = $lokasi['nama_lokasi'];

                    if ($lokasi_presensi == $nama_lokasi) {
                      echo "<option value='$nama_lokasi' selected='selected'>$nama_lokasi</option>";
                    } else {
                      echo "<option value='$nama_lokasi'>$nama_lokasi</option>";
                    }
                  }
                  ?>

                </select>
              </div>

              <!-- Ketika validasi harusnya inputan foto tidak hilang -->
              <div class="mb-3">
                <label for="">Foto</label>
                <input type="text" value="<?= $foto; ?>" name="foto_lama">
                <input type="file" class="form-control" name="foto_baru">
              </div>

              <input type="text" name="id" value="<?= $id; ?>">

              <button type="submit" class="btn btn-primary" name="edit">Update</button>
            </div>
          </div>
        </div>

      </div>
    </form>

  </div>
</div>

<?php include('../layout/footer.php'); ?>