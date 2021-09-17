<?php
require 'functions.php';
$mahasiswa = query("SELECT * FROM mahasiswa");

//Ketika tombol cari diklik
if (isset($_POST["cari"])) {
  $mahasiswa = cari($_POST["keyword"]);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h3>Daftar Mahasiswa</h3>

  <a href="tambah.php">Tambah Data Mahasiswa</a>
  <br><br>

  <form action="" method="POST">
    <input type="text" name="keyword" size="40" placeholder="masukkan keyword pencarian.." autocomplete="off" autofocus>
    <button type="submit" name="cari">Cari!</button>
  </form>
  <br>
  <table border="1" cellspacing="10" cellpadding="">

    <tr>
      <th>#</th>
      <th>Gambar</th>
      <th>Nama</th>
      <th>Aksi</th>
    </tr>

    <?php if (empty($mahasiswa)) : ?>
    <tr>
      <td style="color:red;font-style:italic" colspan="4">
        <p>data mahasiswa tidak ditemukan!</p>
      </td>
    </tr>

    <?php endif; ?>

    <?php $num = 1;
    foreach ($mahasiswa as $m) : ?>
    <tr>
      <td><?= $num++ ?></td>
      <td><img src="img/<?= $m['gambar']; ?>" width="60" height="80"></td>
      <td><?= $m['nama']; ?></td>
      <td>
        <a href="detail.php?id=<?= $m['id']; ?>">lihat detail</a>
      </td>
    </tr>
    <?php endforeach; ?>

</body>

</html>