<?php

$_host = "localhost";
$_user = "root";
$_pass = "";
$_db = "yayasan_cms";

$conn = mysqli_connect($_host, $_user, $_pass, $_db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}