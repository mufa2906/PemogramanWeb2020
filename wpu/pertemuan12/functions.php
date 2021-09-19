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

function tambah($data)
{
  $conn = koneksi();


  $nama = htmlspecialchars($data['nama']);
  $nrp = htmlspecialchars($data['nrp']);
  $email = htmlspecialchars($data['email']);
  $jurusan = htmlspecialchars($data['jurusan']);
  $gambar = htmlspecialchars($data['gambar']);


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
  $gambar = htmlspecialchars($data['gambar']);


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