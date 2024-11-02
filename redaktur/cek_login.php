<?php
include "../config/koneksi.php";

function anti_injection($data) {
  global $conn;
  $filter = mysqli_real_escape_string($conn, $data);
  $filter = stripslashes(strip_tags(htmlspecialchars($filter, ENT_QUOTES)));
  return $filter;
}

$username = isset($_POST['username']) ? anti_injection($_POST['username']) : '';
$password = isset($_POST['password']) ? anti_injection(md5($_POST['password'])) : '';

// Ensure that username and password contain only alphanumeric characters.
if (!ctype_alnum($username) || !ctype_alnum($password)) {
  echo "Sekarang loginnya tidak bisa di injeksi lho.";
} else {
  $login = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password' AND blokir='N'");
  $ketemu = mysqli_num_rows($login);
  $r = mysqli_fetch_array($login);

  // If username and password are found in the database
  if ($ketemu > 0) {
    session_start();
    $_SESSION['namauser'] = $r['username'];
    $_SESSION['namalengkap'] = $r['nama_lengkap'];
    $_SESSION['passuser'] = $r['password'];
    $_SESSION['sessid'] = $r['id_session'];
    $_SESSION['leveluser'] = $r['level'];

    header('Location: media.php?module=home');
  } else {
    echo "
      <link href='css/zalstyle.css' rel='stylesheet' type='text/css'>
      <body class='special-page'>
      <div id='container'>
      <section id='error-number'>
      <img src='img/lock.png'>
      <h1>LOGIN GAGAL</h1>
      <p><span style=\"font-size:14px; color:#ccc;\">Username atau Password anda tidak sesuai.<br>
      Atau akun anda sedang diblokir.</p></span><br/>
      </section>
      <section id='error-text'>
      <p><a class='button' href='index.php'>&nbsp;&nbsp; <b>ULANGI LAGI</b> &nbsp;&nbsp;</a></p>
      </section>
      </div>
    ";
  }
}
?>
