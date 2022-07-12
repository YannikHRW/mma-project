<?php

require_once 'functions.php';
require_once 'index.php';

    if (isLoggedIn()) {
        destroySession();
    } else {
        echo '<script> alert("You cannot log out because you are not logged in!") </script>';
    }
echo '<meta http-equiv="refresh" content="0, URL=index.php">';