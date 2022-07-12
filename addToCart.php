<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

checkIfLoggedIn();

$originURL = $_SERVER['HTTP_REFERER'];
$originDocName = substr($originURL, strrpos($originURL, "/", 1) + 1);
$productID = getIdFromURL();
$productQuantity = $_POST['quantity'];
$userID = $_SESSION['userID'];
$productStock = mysqli_fetch_row(queryMySQL("SELECT stock FROM product WHERE id='$productID'"))[0];

/**
 * Terminates execution of the script with an error message if user wants to buy more then is in stock.
 */
if ($productQuantity > $productStock){
    die("<div class='row'><div class='col-6'><div class='small-box'><p class='error'>There are not enough products in stock! (Stock: $productStock)<br><br> <a href='index.php'>Back to Start!</a></p></div></div></div></section>");
}


/**
 * Alters the shopping cart.
 */
if ($originDocName == "shoppingCart.php") {
    if ($productQuantity == 0) {
        queryMysql("DELETE FROM shoppingCart WHERE userID='$userID' AND productID='$productID'");
    } else {
        queryMysql("UPDATE shoppingCart SET quantity='$productQuantity' WHERE userID='$userID' AND productID='$productID'");
    }
    echo '<meta http-equiv="refresh" content="0, URL=shoppingCart.php">';
    die();
}

$productQuantityOfThisUser = queryMysql("SELECT quantity FROM shoppingCart WHERE userID='$userID' AND productID='$productID'");

/**
 * Adds the products to the shopping cart.
 */
if ($productQuantityOfThisUser->num_rows) {

    $productQuantityOfThisUser = mysqli_fetch_row($productQuantityOfThisUser);
    $currentCartQuantity = $productQuantityOfThisUser[0];

    /**
     * Terminates execution of the script with an error message if the quantity in the cart and those added together exceed the total inventory.
     */
    if ($currentCartQuantity + $productQuantity > $productStock) {
        die("<div class='row'><div class='col-6'><div class='small-box'><p class='error'>There are not enough products in stock! (Stock: $productStock)<br><br> <a href='index.php'>Back to Start!</a></p></div></div></div>/section>");
    } else {
        $totalQuantity = $currentCartQuantity + $productQuantity;
        queryMysql("UPDATE shoppingCart SET quantity='$totalQuantity' WHERE userID='$userID' AND productID='$productID'");
    }

} else {
    queryMysql("INSERT INTO shoppingCart VALUES ('$userID', '$productID', '$productQuantity')");
}

echo '<meta http-equiv="refresh" content="0, URL=index.php">';
