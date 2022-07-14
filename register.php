<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

echo '<div class="row"><div class="col-6"><div class="small-box">';

$error = $firstName = $lastName = $pass = $email = "";
$userType = "USER";

if (isset($_SESSION['name'])) destroySession();

if (isset($_POST['firstName'])) {
    $firstName = sanitizeString($_POST['firstName']);
    $lastName = sanitizeString($_POST['lastName']);
    $email = sanitizeString($_POST['email']);
    $pass = sanitizeString($_POST['pass']);

    if ($firstName == "" || $lastName == "" || $pass == "" || $email == "") {

        $error = "Not all fields were entered!<br>";

    } else {
        $salt1 = "pfdhs";
        $salt2 = "dji45";
        $token = hash('ripemd128', "$salt1$pass$salt2");

        $userData = queryMySQL("SELECT * FROM user WHERE email='$email'");

        /**
         * first user is automatically a admin..
         */
        if (getAccountsTotal() === 0) {
            $userType = "ADMIN";
        }

        if ($userData->num_rows) {
            $error = "This E-Mail is already in use<br><br>";
        } else {
            queryMysql("INSERT INTO user VALUES(NULL, '$firstName', '$lastName', '$email', '$token', '$userType')");
            die("<h4>Account created</h4>Please log in.<br><br> <a href='login.php'>Log in</a></div></div></div></section>");
        }
    }
}
echo <<<_END
            <h1>Register</h1>
            
            <form action="register.php" method="post">$error

                <div>
                    <label for="input-firstName">First Name:</label>
                    <input id="input-firstName" type="text" maxlength="16" name="firstName" placeholder="First Name" value="$firstName">
                </div>
                
                <div>
                    <label for="input-lastName">Last Name:</label>
                    <input id="input-lastName" type="text" maxlength="16" name="lastName" placeholder="Last Name" value="$lastName">
                </div>
                
                <div>
                    <label for="input-email">E-Mail:</label>
                    <input id="input-email" type="email" name="email" maxlength="32" placeholder="E-Mail" value="$email" pattern="(?:[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*|\\u0022(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\\u0022)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])">
                </div>

                <div>
                    <label for="input-password">Password:</label>
                    <input id="input-password" type="password" name="pass" maxlength="32" placeholder="Password" value="$pass">
                </div>
            _END;
?>
<div>
    <button class="btn-typ-1" type="submit">Register!</button>
</div>

</form>

<div>
    <p>Do you already have an account?</p>
    <a href="login.php" class="btn-typ-1" id="login-button">Log in!</a>
</div>

</div>
</div>
</div>

</section>

<script type="text/javascript" src="js/main.js"></script>
</body>
</html>