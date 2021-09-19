<?php
require 'functions.php';

//Jika button registrasi ditekan 
if (isset($_POST['registrasi'])) {
  if (registrasi($_POST) > 0) {
    echo  "<script>
            alert('User baru berhasil ditambahkan, Silahkan Login!')
            document.location.href = 'login.php'
          </script>";
    return false;
  };
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Registrasi</title>
</head>

<body>
  <form action="" method="POST">
    <ul>
      <li>
        <label>
          Username
          <input type="text" name="username" autocomplete="off" autofocus required>
        </label>
      </li>
      <li>
        <label>
          Password
          <input type="password" name="password1" required>
        </label>
      </li>
      <li>
        <label>
          Konfirmasi Password
          <input type="password" name="password2" required>
        </label>
      </li>
      <li>
        <button type="submit" name="registrasi">Registrasi</button>
      </li>
    </ul>
  </form>
</body>

</html>