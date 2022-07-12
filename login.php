<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

echo '<div class="row"><div class="col-6"><div class="small-box">';

$error = $email = $pass = "";

if (isset($_POST['email'])) {
    $email = sanitizeString($_POST['email']);
    $pass = sanitizeString($_POST['pass']);

    if ($email == "" || $pass == "")
        $error = "Not all fields were entered!<br>";
    else {
        $salt1 = "pfdhs";
        $salt2 = "dji45";
        $token = hash('ripemd128', "$salt1$pass$salt2");

        $userData = queryMySQL("SELECT email, pass FROM user WHERE email='$email' AND pass='$token'");

        if ($userData->num_rows == 0) {
            $error = "<span class='error'>E-Mail/Password invalid!</span><br><br>";
        } else {

            $user = queryMysql("SELECT id, firstName, userType FROM user WHERE email='$email'");
            $userArray = mysqli_fetch_row($user);
            $_SESSION['userID'] = $userArray[0];
            $_SESSION['firstName'] = $userArray[1];
            $_SESSION['userType'] = $userArray[2];

            if (!isset($_SESSION['originDocName']) || $_SESSION['originDocName'] === "register.php") {
                $_SESSION['originDocName'] = "index.php";
            }
            $originDocName = $_SESSION['originDocName'];
            echo "<meta http-equiv='refresh' content='0, URL=$originDocName'>";
        }
    }
} else {

    /**
     * Safes the origin document name from where the login is entered, to return back to this after the login.
     */
    $originURL = $_SERVER['HTTP_REFERER'];
    $_SESSION['originDocName'] = substr($originURL, strrpos($originURL, "/", 1) + 1);
}
echo <<<_END
                <h1>Log In</h1>

                <form id="login-form" action="login.php" method="post">$error

                    <div>
                        <label for="input-email">E-Mail:</label>
                        <input id="input-email" type="email" maxlength="32" name='email' placeholder="E-Mail" value='$email' pattern="(?:[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*|\\u0022(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\\u0022)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])">
                    </div>

                    <div>
                        <label for="input-password">Password:</label>
                        <input id="input-password" type="password" name='pass' maxlength="32" placeholder="Password" value='$pass'>
                    </div>
                _END;
?>
                    <div>
                        <button class="btn-typ-1" type="submit">Log In!</button>
                    </div>

                </form>

                    <div>
                        <p>Not registered yet?</p>
                        <a href="register.php" class="btn-typ-1" id="register-button">Register Now!</a>
                    </div>

                </div>
            </div>
        </div>

    </section>

</body>
</html>