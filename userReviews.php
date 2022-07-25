<?php

require_once 'functions.php';
require_once 'header.php';

echo <<<_END
            <script>

                /**
                * Sets the color of the selected nav-element.
                * @type {HTMLCollectionOf<Element>}
                */
                let navReviews = document.getElementsByClassName("nav-reviews");
                for (const navReview of navReviews) {
                  navReview.classList.add("active");
                }
        
            </script>
            
            <!-- HEADER-BANNER -->

            <header id="header-banner-small"></header>
        _END;

echo <<<_END

    <!-- REVIEWS-SECTION -->

                <section id="reviews-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <header class="intro-container">
                                    <h1>Your Reviews</h1>
                                </header>
                            </div>
                        </div>
    _END;

checkIfLoggedIn();

$userID = $_SESSION['userID'];
$productReviews = queryMysql("SELECT * FROM productReview WHERE userID='$userID'");

$productReviewsArray = mysqli_fetch_all($productReviews);

if ($productReviews->num_rows == 0) {
    echo "<div class='row'><div class='col-6'><div class='small-box'><p>You haven't rated any product yet!</p><a class='btn-typ-1' href='index.php'>Back to start!</a></div></div></div></div>";
    require_once "footer.html";
    die();
}

echo                    '<div id="article-rows">';

$i = 0;

/**
 * Creates review boxes.
 */
foreach ($productReviewsArray as $review) {

    $ratedProducts = queryMysql("SELECT * FROM product WHERE id='$review[1]'");
    $ratedProductsArray = mysqli_fetch_row($ratedProducts);

    if ($i === 0) {
        echo '<div class="row">';
    }

    echo <<<_END
                                <div class="col-2">
                                    <article class="article-box">
                                    <h2 class="searchable">$ratedProductsArray[1]</h2>
                                    <img class='product-img' src='img/productImg/$ratedProductsArray[6]' alt='Kein Bild vorhanden...'>
                                    <p>
            _END;
                                    createRatingScale($review[2]);
    echo <<<_END
                                    </p>
                                    <h4>Comment:</h4>
                                    <p class="searchable">$review[3]</p>
                                    <div>
                                        <a href="productReviews.php?id=$ratedProductsArray[0]" style="text-decoration: none">
                                            <button class="btn-typ-1 change-btn" type="button">Edit review!</button>
                                        </a>
                                    </div>
                                    </article>
                                </div>
                _END;

    $i++;
    if ($i === 3) {
        echo            '</div>';
        $i = 0;
    }
}
if (count($productReviewsArray) % 3 != 0) {
    echo            '</div>';
}

echo '</div></div></section>';

require_once 'footer.html';

?>