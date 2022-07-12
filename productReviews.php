<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

$productID = getIdFromURL();
$isAdmin = isAdmin();

if (isset($_SESSION['userID'])){

    $userID = $_SESSION['userID'];

    /**
     * Deletes the review of the user.
     */
    if (isset($_POST['delete-mine'])) {

        queryMysql("DELETE FROM productReview WHERE productID='$productID' AND userID='$userID'");
        echo "<script> alert('Your review has been deleted!') </script><meta http-equiv='refresh' content='0, URL=productReviews.php?id=$productID'>";

        calcAverageRating($productID);

    }

    /**
     * Deletes the review from an other user if this user is admin.
     */
    if ($isAdmin) {
        if (isset($_POST['delete-users'])) {

            $reviewUserID = filter_var($_GET["userID"], FILTER_SANITIZE_STRING);
            queryMysql("DELETE FROM productReview WHERE productID='$productID' AND userID='$reviewUserID'");
            echo "<script> alert('Review from user has been deleted!')</script><meta http-equiv='refresh' content='0, URL=productReviews.php?id=$productID'>";

            calcAverageRating($productID);

        }
    }

    if (isset($_POST['comment'])) {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];


        /**
         * Safes the comment.
         */
        $userComment = queryMysql("SELECT comment FROM productReview WHERE userID='$userID' AND productID='$productID'");
        if ($userComment->num_rows == 0) {
            queryMysql("INSERT INTO productReview VALUES('$userID', '$productID', '0', '$comment')");
        } else {
            queryMysql("UPDATE productReview SET comment='$comment' WHERE userID='$userID' AND productID='$productID'");
        }

        /**
         * Safes the rating.
         */
        $userRating = queryMysql("SELECT rating FROM productReview WHERE userID='$userID' AND productID='$productID'");
        if ($userRating->num_rows == 0) {
            queryMysql("INSERT INTO productReview VALUES('$userID', '$productID', '$rating', NULL)");
        } else {
            queryMysql("UPDATE productReview SET rating='$rating' WHERE userID='$userID' AND productID='$productID'");
        }

        calcAverageRating($productID);

    }

    $userHasRated = "Review product!";

    /**
     * Creates empty review array if user hasn't rated so far.
     */
    $thisUserHasRated = queryMysql("SELECT * FROM productReview WHERE userID='$userID' AND productID='$productID'");
    if ($thisUserHasRated->num_rows) {
        $reviewArray = mysqli_fetch_row($thisUserHasRated);
        $userHasRated = "Edit review!";
    } else {
        $reviewArray = [$userID, $productID, 0, ""];
    }

    $productRating = $reviewArray[2];
    $productComment = $reviewArray[3];

    echo <<<_END
                <div class="row">
                    <div class="col-6">
                        <div class='small-box'>
                            <h1>Your review:</h1>
                            <form action='productReviews.php?id=$productID' method='post'>
                                <input name='rating' value='$productRating' style='display: none'>
                                    Rating:
                                    <span class="changeable-rating-scale">
    _END;
                                        createRatingScale($productRating);
    echo <<<_END
                                    </span>           
                                <div>
                                    <label for="input-comment">Write a comment:</label>
                                    <textarea id="input-comment" cols="36" rows="10" name="comment">$productComment</textarea>
                                </div>
                                <div>
                                    <button class='btn-typ-1' type='submit'>$userHasRated</button>
                                </div>
                            </form>
                            <form action="productReviews.php?id=$productID" method="post">                     
                                <div>
                                    <button class="btn-typ-1 delete-btn" type="submit" name="delete-mine">Delete review!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            _END;
}

$allReviews = queryMysql("SELECT * FROM productReview WHERE productID='$productID'");


/**
 * Creates a info box if no one has reviewed the product. Otherwise the review boxes.
 */
if ($allReviews->num_rows == 0) {
    echo "<div class='small-box'><p>No one has rated it so far.</p>";
    if (isset($_SESSION['userID'])) {
        echo "<p><strong>Be the first one and leave a comment!</strong></p></div>";
    } else {
        echo "</div>";
    }
} else {

    $reviewsArray = mysqli_fetch_all($allReviews);

    foreach ($reviewsArray as $review) {

        $orders = queryMysql("SELECT * FROM orders WHERE userID='$review[0]' AND productID='$review[1]'");
        $user = mysqli_fetch_row(queryMysql("SELECT firstName FROM user WHERE id='$review[0]'"));
        $user = $user[0];

        $userID = $review[0];
        $productRating = $review[2];
        $productComment = $review[3];

        echo <<<_END
                <div class="row">
                    <div class="col-6">
            _END;

        $userBought = "";

        /**
         * Indicates if a user which reviewed the product actually bought it.
         */
        if ($orders->num_rows) {
            $userBought = "<h4>This user actually bought the product.</h4>";
        }

        echo <<<_END
                        <div class='small-box'>
                            <h1>Review from User: $user</h1>
                            $userBought
                            <p>Rating: 
            _END;

        createRatingScale($productRating);

        echo <<<_END
                            </p>
                            <p>$productComment</p>
                _END;

        /**
         * Button for admin to delete other reviews.
         */
        if ($isAdmin) {
            echo <<<_END
                            <form action="productReviews.php?id=$productID&userID=$userID" method="post">                     
                                <div>
                                    <button class="btn-typ-1 delete-btn" type="submit" name="delete-users">Delete review!</button>
                                </div>
                            </form>
                    _END;
        }

        echo <<<_END
                        </div>
                    </div>
                </div>
            _END;
    }
}
?>
        <script type="text/javascript" src="js/main.js"></script>
    </body>
</html>
