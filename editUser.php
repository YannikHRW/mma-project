<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

checkIfUserIsAdmin();

echo "<div class='row'><div class='col-6'><div class='small-box'>";

$userID = getIdFromURL();
$user = queryMysql("SELECT * FROM user WHERE id='$userID'");
$userArray = mysqli_fetch_row($user);

$error = "";
$isUser = $isAdmin = "";

if ($userArray[5] === "ADMIN") {
    $isAdmin = "checked";
} else {
    $isUser = "checked";
}

if (isset($_POST['firstName'])) {
    $firstName = sanitizeString($_POST['firstName']);
    $lastName = sanitizeString($_POST['lastName']);
    $email = sanitizeString($_POST['email']);
    $userType = sanitizeString($_POST['userType']);

    if ($firstName == "" || $lastName == "" || $email == "")
        $error = "Not all fields were entered!<br>";
    else {
        queryMysql("UPDATE user SET firstName='$firstName', lastName='$lastName', email='$email', userType='$userType' WHERE id='$userID'");
        die("<h4>User with ID $userID edited</h4>Back to <p><a href='userList.php'>Userlist</a></p></div></div></div></div></section>");
    }
}
echo <<<_END
                <h1>Edit $userArray[5] with ID $userArray[0]</h1>
                
                <form action="editUser.php?id=$userID" method="post">$error

                    <div>
                        <label for="input-firstName">First Name:</label>
                        <input id="input-firstName" type="text" maxlength="16" name="firstName" placeholder="First Name" value="$userArray[1]">
                    </div>
                    
                    <div>
                        <label for="input-lastName">Last Name:</label>
                        <input id="input-lastName" type="text" maxlength="16" name="lastName" placeholder="Last Name" value="$userArray[2]">
                    </div>
                    
                    <div>
                        <label for="input-email">E-Mail:</label>
                        <input id="input-email" type="email" name="email" maxlength="32" placeholder="E-Mail" value="$userArray[3]" pattern="(?:[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*|\\u0022(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\\u0022)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])">
                    </div>
                    
                    <div>
                        <input type="radio" name='userType' value='ADMIN' $isAdmin>Admin
                        <input type="radio" name='userType' value='USER' $isUser>User
                    </div>

                    <div>
                        <button class="btn-typ-1" type="submit">Edit!</button>
                    </div>

                </form>
                _END;
?>
    </div>
    </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>