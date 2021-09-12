<?php
// koneksi ke DB & Pilih Database
$conn = mysqli_connect('localhost', 'root', '', 'pwfarhan');


//Query isi tabel mahasiswa 
$result = mysqli_query($conn, "SELECT * FROM mahasiswa");



// Ubah data ke dalam array
// $row = mysqli_fetch_row($result); array numeric
// $row = mysqli_fetch_assoc($result); array associative 
// $row = mysqli_fetch_array($result); keduanya
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
  $rows[] = $row;
}


// Tampung ke variabel mahasiswa 
$mahasiswa = $rows;
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


  <table border="1" cellspacing="10" cellpadding="">

    <tr>
      <th>#</th>
      <th>Gambar</th>
      <th>NRP</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Jurusan</th>
      <th>Aksi</th>
    </tr>

    <?php $num = 1;
    foreach ($mahasiswa as $m) : ?>
    <tr>
      <td><?= $num++ ?></td>
      <td><img src="img/<?= $m['gambar']; ?>" width="60" height="80"></td>
      <td><?= $m['nrp']; ?></td>
      <td><?= $m['nama']; ?></td>
      <td><?= $m['email']; ?></td>
      <td><?= $m['jurusan']; ?></td>
      <td>
        <a href="">ubah</a> | <a href="">hapus</a>
      </td>
    </tr>
    <?php endforeach; ?>

</body>

</html>