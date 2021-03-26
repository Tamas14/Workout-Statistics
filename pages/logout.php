<?
setcookie('auth_ID', "1", time()-3600, $cookiePath, $cookieDomain);
setcookie('auth_NAME', "1", time()-3600, $cookiePath, $cookieDomain);
setcookie('welcome', "1", time()-3600, $cookiePath, $cookieDomain);

header ("Location: ../index.php");
?>