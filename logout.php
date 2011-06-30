<?php

include "funcLib.php";

session_name("WishListSite");
session_start();
session_destroy();

header("Location: " . getFullPath("login.php"));

?>