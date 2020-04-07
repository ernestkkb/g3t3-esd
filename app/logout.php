<?php

session_destroy();
unset($_SESSION['access_token']);
header("Location:https://g3t3-ui.herokuapp.com/app/login.php");
