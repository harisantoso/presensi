<?php

session_unset();
session_destroy();

header("Location: ../index.php");


// * saat sudah logout masih bisa mengakses user
// * atau session tidak hilang
