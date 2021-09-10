<?php
function salam($waktu = "Datang",$nama = "Admin") {
    return "Selamat $waktu, $nama!";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Latihan Functions</title>
</head>
<body>
    <h1><?= salam(); ?></hh1>
</body>
</html>