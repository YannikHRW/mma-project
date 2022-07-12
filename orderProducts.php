<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

checkIfLoggedIn();

$userID = $_SESSION['userID'];

$productsInCart = queryMysql("SELECT productID, quantity FROM shoppingCart WHERE userID='$userID'");
$productsInCartArray = mysqli_fetch_all($productsInCart);

$currentDatetime = date("Y-m-d H:i:s");

foreach ($productsInCartArray as $product) {

    $productID = $product[0];
    $quantity = $product[1];

    $productStock = queryMysql("SELECT stock FROM product WHERE id='$product[0]'");
    $productStock = mysqli_fetch_row($productStock);

    $newStock = $productStock[0] - $quantity;

    queryMysql("INSERT INTO orders VALUES ('$userID', '$productID', '$quantity', '$currentDatetime')");
    queryMysql("DELETE FROM shoppingCart WHERE userID='$userID' AND productID='$productID'");
    queryMysql("UPDATE product SET stock='$newStock' WHERE id='$productID'");

}
echo '<meta http-equiv="refresh" content="0, URL=index.php">';