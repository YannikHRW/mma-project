<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

checkIfUserIsAdmin();

echo "<div class='row'><div class='col-6'><div class='small-box'>";

$firstName = $lastName = $email = $pass = $userType = $error = "";

if (isset($_POST['firstName'])) {
    $firstName = sanitizeString($_POST['firstName']);
    $lastName = sanitizeString($_POST['lastName']);
    $userType = sanitizeString($_POST['userType']);
    $email = sanitizeString($_POST['email']);
    $pass = sanitizeString($_POST['pass']);


    if ($firstName == "" || $lastName == "" || $email == "" || $pass == "") {

        $error = "<br><span class='error'>Not all fields were entered</span>";

    } else {

        $salt1 = "pfdhs";
        $salt2 = "dji45";
        $token = hash('ripemd128', "$salt1$pass$salt2");

        $userWithThisEmail = queryMySQL("SELECT * FROM user WHERE email='$email'");

        if ($userWithThisEmail->num_rows) {

            $error = "<span class='error'>This E-Mail is already in use!</span><br><br>";

        } else {

            queryMysql("INSERT INTO user VALUES(NULL, '$firstName', '$lastName', '$email', '$token', '$userType')");
            die("<h4>User created</h4>
                  <p>Add more?</p>
                  <p><a href='addUser.php'>Add next user</a></p>
                  <p>Back to</p>
                  <p><a href='userList.php'>User list</a></p>
                  <p>Back to</p>
                  <p><a href='index.php'>Start</a></p>
                  </div></div></div></div></section>");
        }
    }
}
echo <<<_END

            <h1>Add a new User</h1>
            
            <form class="change/create-form" action="addUser.php" method="post">$error
        
                <div>
                    <label for="input-firstName">First Name:</label>
                    <input id="input-firstName" type="text" maxlength="32" name='firstName' placeholder="First Name" value='$firstName'>
                </div>
            
                <div>
                    <label for="input-lastName">Last Name:</label>
                    <input id="input-lastName" type="text" maxlength="32" name='lastName' placeholder="Last Name">$lastName</input>
                </div>
        
                <div>
                    <label for="input-email">E-Mail:</label>
                    <input id="input-email" type="email" name='email' maxlength="32" placeholder="E-Mail" value="$email">
                </div>
        
                <div>
                    <input type="radio" name='userType' value='USER' checked>User
                    <input type="radio" name='userType' value='ADMIN'>Admin
                </div>
        
                <div>
                    <label for="input-pass">Password:</label>
                    <input id="input-pass" type="password" name='pass' maxlength="32" placeholder="Password" value="$pass">
                </div>
        
        _END;
?>
                <div>
                    <button class="btn-typ-1" type="submit">Add User!</button>
                </div>
                <div>
                    <button class="btn-typ-1" id="reset-btn" type="reset">Reset input!</button>
                </div>

            </form>

        </div>
        </div>
        </div>
    </section>

    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>