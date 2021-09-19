<?php

function koneksi()
{
  return  mysqli_connect('localhost', 'root', '', 'pwfarhan');
}

function query($query)
{
  $conn = koneksi();

  $result = mysqli_query($conn, $query);

  //Jika hasilnya hanya 1 data
  if (mysqli_num_rows($result) == 1) {
    return mysqli_fetch_assoc($result);
  }

  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function upload()
{
  $nama_file = $_FILES['gambar']['name'];
  $tipe_file = $_FILES['gambar']['type'];
  $ukuran_file = $_FILES['gambar']['size'];
  $error = $_FILES['gambar']['error'];
  $tmp_file = $_FILES['gambar']['tmp_name'];

  //Ketika tidak ada gambar yang dipilih(Pengamanan melalui php)
  if ($error == 4) {
    // echo "<script>
    //       alert('pilih gambar dahulu!!');
    //     </script>";
    return 'nophoto.png';
  }

  //cek ekstensi files
  $daftar_gambar = ['jpg', 'png', 'jpeg'];
  $ekstensi_file = explode('.', $nama_file);
  $ekstensi_file = strtolower(end($ekstensi_file));
  if (!in_array($ekstensi_file, $daftar_gambar)) {
    echo "<script>
            alert('Format gambar tidak sesuai');
          </script>";
    return false;
  }

  //cek type file
  if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/png') {
    echo "<script>
            alert('Tipe file bukan gambar');
          </script>";
    return false;
  }

  //cek ukuran file(Max 5 mb == 5000000)
  if ($ukuran_file > 5000000) {
    echo "<script>
            alert('Ukuran terlalu besar. Max 5 MB');
          </script>";
    return false;
  }

  //lolos pengecekan file
  //siap upload file
  //generate nama file gambar yang diupload
  $nama_file_gambar = uniqid();
  $nama_file_gambar .= '.';
  $nama_file_gambar .= $ekstensi_file;
  move_uploaded_file($tmp_file, 'img/' . $nama_file_gambar);
  return $nama_file_gambar;
}

function tambah($data)
{
  $conn = koneksi();

  //Penanganan permasalhan dengan HTML
  $nama = htmlspecialchars($data['nama']);
  $nrp = htmlspecialchars($data['nrp']);
  $email = htmlspecialchars($data['email']);
  $jurusan = htmlspecialchars($data['jurusan']);
  // $gambar = htmlspecialchars($data['gambar']);

  //Gambar ketika berupa file, cara upload
  $gambar = upload();

  if (!$gambar) {
    return false;
  }

  $query = "INSERT INTO 
            mahasiswa
            VALUES
            (null, '$nama','$nrp','$email','$jurusan','$gambar')
          ";
  mysqli_query($conn, $query) or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}

function hapus($id)
{
  $conn = koneksi();

  //menghapus gambar di folder img
  $mhs = query("SELECT * FROM mahasiswa WHERE id = '$id'");
  if ($mhs['gambar'] != 'nophoto.png') {
    unlink('img/' . $mhs['gambar']);
  }


  mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = '$id'") or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}

function ubah($data)
{
  $conn = koneksi();

  $id = $data['id'];
  $nama = htmlspecialchars($data['nama']);
  $nrp = htmlspecialchars($data['nrp']);
  $email = htmlspecialchars($data['email']);
  $jurusan = htmlspecialchars($data['jurusan']);
  $gambar_lama = htmlspecialchars($data['gambar_lama']);

  $gambar = upload();
  if (!$gambar) {
    return false;
  }

  if ($gambar == 'nophoto.png') {
    $gambar = $gambar_lama;
  }

  $query = "UPDATE mahasiswa SET
            nama = '$nama',
            nrp = '$nrp',
            email = '$email',
            jurusan = '$jurusan',
            gambar = '$gambar'
            WHERE id = '$id'";
  mysqli_query($conn, $query) or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}

function cari($keyword)
{
  $conn = koneksi();

  $query = "SELECT * FROM mahasiswa 
            WHERE 
            nama LIKE '%$keyword%' or 
            nrp LIKE '%$keyword%' or
            email LIKE '%$keyword%'or
            jurusan LIKE '%$keyword%'";
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function login($data)
{
  $conn = koneksi();

  $username = htmlspecialchars($data['username']);
  $password = htmlspecialchars($data['password']);

  //cek username
  if ($user = query("SELECT * FROM user WHERE username ='$username'")) {
    //cek password
    if (password_verify($password, $user['password'])) {
      //set session
      $_SESSION['login'] = true;

      header("Location:index.php");
      exit;
    }
  }
  return [
    'error' => true,
    'pesan' => 'Username /Password Salah'
  ];
}

function registrasi($data)
{
  $conn = koneksi();

  $username = htmlspecialchars(strtolower($data['username']));
  $password1 = mysqli_real_escape_string($conn, $data['password1']);
  $password2 = mysqli_real_escape_string($conn, $data['password2']);

  // jika ada yang kosong
  if (empty($username) || empty($password1) || empty($password2)) {
    echo "<script>
            alert('Username / Password harus diisi')
             document.location.href = 'registrasi.php'
          </script>";
    return false;
  }

  //jika username sudah ada
  if (query("SELECT * FROM user WHERE username ='$username'")) {
    echo "<script>
            alert('Username telah terdaftar')
             document.location.href = 'registrasi.php'
          </script>";
    return false;
  }

  //jika konfirmasi password tidak sama
  if ($password1 !== $password2) {
    echo "<script>
              alert('Password tidak sama')
               document.location.href = 'registrasi.php'
            </script>";
    return false;
  }

  //Jika password <6
  if (strlen($password1) <= 6) {
    echo "<script>
              alert('Password harus terdiri dari 6 digit')
               document.location.href = 'registrasi.php'
            </script>";
    return false;
  }

  //Jika username & password sudah seusai
  //enkripsi password
  $password_baru = password_hash($password1, PASSWORD_DEFAULT);
  //insert ke table user
  $query = "INSERT INTO user
              VALUES
              (null, '$username', '$password_baru')
              ";
  mysqli_query($conn, $query) or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}