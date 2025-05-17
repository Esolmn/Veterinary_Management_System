<?php

    session_start();

    session_unset(); //inaalis mga naka set session variables

    session_destroy();// end session

    header('Location: login.php');

?>