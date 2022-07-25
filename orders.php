<?php

require_once 'functions.php';
require_once 'header.php';

echo <<<_END
            <script>
        
                /**
                * Sets the color of the selected nav-element.
                * @type {HTMLCollectionOf<Element>}
                */
                let navOrders = document.getElementsByClassName("nav-orders");
                for (const navReview of navOrders) {
                  navReview.classList.add("active");
                }
        
            </script>

            <!-- HEADER-BANNER -->

            <header id="header-banner-small"></header>

            <!-- ORDERS-SECTION -->

            <section id="orders-section">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <header class="intro-container">
                                <h1>Your Orders</h1>
                            </header>
                        </div>
                    </div>
    _END;

checkIfLoggedIn();

$userID = $_SESSION['userID'];
$ordersFromUser = queryMysql("SELECT * FROM orders WHERE userID='$userID'");

if ($ordersFromUser->num_rows == 0) {
    echo "<div class='row'><div class='col-6'><div class='small-box'><p class='error'>You haven't ordered anything yet!</p><a class='btn-typ-1' href='index.php'>Back to start!</a></div></div></div></div></section>";
    require_once "footer.html";
    die();
}

$ordersFromUserArray = mysqli_fetch_all($ordersFromUser);
$ordersFromUserArrayReversed = array_reverse($ordersFromUserArray);

echo '<div id="article-rows">';

$i = 0;

$sum = 0;


/**
 * creates boxes with all the orders of one day.
 */
foreach ($ordersFromUserArrayReversed as $order) {

    $productID = $order[1];
    $quantity = $order[2];
    $date = $order[3];

    $newDate = $date;
    if (!isset($oldDate) || $newDate !== $oldDate) {

        /**
         * closes the box if the date changes and if its not the first box.
         */
        if (isset($oldDate)) {
            echo "<h4>Total costs:</h4><p class='searchable'> " . $sum . "€</p></article></div>";
            $sum = 0;
            $i++;

            /**
             * closes the row if three boxes are created.
             */
            if ($i === 3) {
                echo            '</div>';
                $i = 0;
            }
        }

        if ($i === 0) {
            echo '<div class="row">';
        }

        /**
         * opens a nex box.
         */
        echo "<div class='col-2'><article class='article-box'><h3>All orders from: $date</h3>";
    }

    $product = queryMysql("SELECT * FROM product WHERE id='$productID'");
    $productArray = mysqli_fetch_row($product);

    $productTitle = $productArray[1];
    $productDescription = $productArray[2];
    $productPrice = $productArray[3];
    $productImageName = $productArray[6];

    echo <<<_END
                <h4 class="searchable">$productTitle</h4>
                <img class='product-img' src='img/productImg/$productImageName' alt='Kein Bild vorhanden...'>
                <h5>Description:</h5><textarea class="searchable" readonly>$productDescription</textarea>
            _END;

    $priceTotal = $productPrice * $quantity;
    $sum += $priceTotal;

    echo "<h5>Costs:</h5><p class='searchable'>$productPrice" . "€ * " . $quantity . " = " . $priceTotal . "€</p><br>";

    $oldDate = $newDate;

}

/**
 * closes last box
 */
echo "<h4>Total costs:</h4><p class='searchable'> " . $sum . "€</p>";
if (count($ordersFromUserArrayReversed) % 3 != 0) {
    echo            '</article></div>';
}


echo '</div></div></div></section>';

require_once 'footer.html';

?>