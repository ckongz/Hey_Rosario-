<?php
session_start();
session_unset();
session_destroy();
if (isset($_COOKIE['remember_token'])) setcookie('remember_token','',time()-3600,'/');
session_start();
header("Location: ../login.php?success=You+have+been+logged+out+successfully.");
exit();
