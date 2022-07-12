<?php

require_once 'functions.php';
require_once 'header.php';

echo <<<_END
            <script>
        
                /**
                * Sets the color of the selected nav-element.
                * @type {HTMLCollectionOf<Element>}
                */
                let navUserLists = document.getElementsByClassName("nav-user-list");
                for (const navUserList of navUserLists) {
                  navUserList.classList.add("active");
                }
        
            </script>
            <!-- HEADER-BANNER -->

            <header id="header-banner-small"></header>

            <!-- REVIEWS-SECTION -->

                <section id="reviews-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <header class="intro-container">
                                    <h1>All users</h1>
                                </header>
                            </div>
                        </div>
    _END;

checkIfUserIsAdmin();

echo <<<_END
                        <div class="row">
                            <div class="col-6">
                                <a href="addUser.php">
                                    <div class="btn-typ-1 add-btn">
                                        <i class="fas fa-plus"></i>                            
                                    </div>
                                </a>
                            </div>
                        </div>
        _END;

echo '<div id="article-rows">';

$allUsers = queryMysql("SELECT * FROM user");
$allUsersArray = mysqli_fetch_all($allUsers);

/**
 * Deletes a user.
 */
if (isset($_POST['delete'])) {
    $userID = getIdFromURL();
    queryMysql("DELETE FROM user WHERE id='$userID'");
    echo "<script> alert('User has been deleted!') </script><meta http-equiv='refresh' content='0, URL=userList.php'>";
}

$i = 0;

/**
 * Creates user boxes.
 */
foreach ($allUsersArray as $user) {

    if ($i === 0) {
        echo '<div class="row">';
    }

    echo <<<_END
          
            <div class="col-2">
                <article class="article-box">

                    <h4>ID: </h4><p class="searchable">$user[0]</p>
                    <h4>First Name: </h4><p class="searchable">$user[1]</p>
                    <h4>Last Name: </h4><p class="searchable">$user[2]</p>
                    <h4>E-Mail: </h4><p class="searchable">$user[3]</p>
                    <h4>Usertype: </h4><p class="searchable">$user[5]</p>
                    <div>
                        <a href="editUser.php?id=$user[0]" style="text-decoration: none"><button class="btn-typ-1 change-btn" type="button">Edit user!</button></a>
                    </div>
                    <form action="userList.php?id=$user[0]" method="post">
                        <div>
                            <button class="btn-typ-1 delete-btn" type="submit" name="delete">Delete user!</button>
                        </div>
                    </form>
                    
                </article>
            </div>

          _END;

    $i++;
    if ($i === 3) {
        echo            '</div>';
        $i = 0;
    }
}

if (count($allUsersArray) % 3 != 0) {
    echo            '</div>';
}

echo '</div></div></section>';

require_once 'footer.html';

?>