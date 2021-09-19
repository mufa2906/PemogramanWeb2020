<?php
require '../functions.php';

$mahasiswa = cari($_GET["keyword"]);
?>
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
</table>