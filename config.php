<?php

$connection = mysqli_connect("localhost", "root", "", "presensi");

if (!$connection) {
  echo "Koneksi database gagal!!", mysqli_connect_error();
}

function base_url($url = null)
{
  $base_url = 'http://localhost/presensi';
  if ($url != null) {
    return $base_url . '/' . $url;
  } else {
    return $base_url;
  }
}
