<?php
$dbhost  = 'localhost';
$dbname  = 'webshop';
$dbuser  = 'root';
$dbpass  = '';
$appname = "WebShop";

$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_error) die($connection->connect_error);

function createTable($name, $query) {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
}

function queryMysql($query) {
    global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
}

function destroySession() {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');

        echo '<script> alert("You have been logged out!") </script>';
        echo '<meta http-equiv="refresh" content="0, URL=index.php">';
        die();
    }

    session_destroy();
}

function sanitizeString($var) {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}

/*function console_log($data){
    echo '<script> console.log('.json_encode($data).') </script>';
}*/

/**
 * Sets a session timeout on 30 minutes. After that the user will be automatically  logged out.
 *
 * @return void
 */
function session_timeout(){

    if (isLoggedIn()) {

        $session_timeout = 1800;

        if (!isset($_SESSION['last_visit'])) {
            $_SESSION['last_visit'] = time();
        }
        if ((time() - $_SESSION['last_visit']) > $session_timeout) {
            destroySession();
        }
        $_SESSION['last_visit'] = time();
    }
}

/**
 * @return int the total amount of registered accounts.
 */
function getAccountsTotal():int {
    $result = queryMysql("SELECT COUNT(id) FROM user");
    if ($result){
        return (mysqli_fetch_row($result))[0];
    }
    return 0;
}

/**
 * @return bool the admin status.
 */
function isAdmin():bool {
    return isset($_SESSION['userType']) && $_SESSION['userType'] === "ADMIN";
}

/**
 * Terminates execution of the script with an error-box iff user is not a admin.
 *
 * @return void
 */
function checkIfUserIsAdmin() {
    if (!isAdmin()) {
        die("<div class='row'><div class='col-6'><div class='small-box'><p class='error'>You are not authorized to do that!</p><a class='btn-typ-1' href='index.php'>Back to start!</a></div></div></div>");
    }
}

/**
 * @return bool the login status.
 */
function isLoggedIn():bool {
    return isset($_SESSION['userID']);
}

/**
 * Terminates execution of the script with an error-box iff user is not logged in.
 *
 * @return void
 */
function checkIfLoggedIn() {
    if (!isLoggedIn()){
        die("<div class='row'><div class='col-6'><div class='small-box'><p class='error'>Your are not logged in!</p><a class='btn-typ-1' href='login.php'>Log in!</a></div></div></div>");
    }
}

/**
 * @return mixed id-variable from the URL.
 */
function getIdFromURL() {
    return filter_var($_GET["id"], FILTER_SANITIZE_STRING);
}

/**
 * If an image is uploaded, check if it is a .png or .jpg and store it in the project. Otherwise terminate execution of the script with an error message.
 */
function validateFile($imageName, $href) {
    if ($imageName !== "") {
        $extension = substr($imageName, strrpos($imageName,'.', -1) + 1, strlen($imageName));
        if ($extension !== 'png' && $extension !== 'jpg'){
            die("<br><span class='error'>This file is not allowed! ( => .png, .jpg)</span><br><br> <a href='$href'>Go Back!</a></div></div></div></div></section>");
        }
        move_uploaded_file($_FILES['upload-file']['tmp_name'], 'img/productImg/' . $imageName);
    }
}

/**
 * Calculates the new rating average for the product.
 *
 * @param $productID
 * @return void
 */
function calcAverageRating($productID){
    $allRatingsForThisProduct = queryMysql("SELECT rating FROM productReview WHERE productID='$productID'");
    $allRatingsForThisProductArray = mysqli_fetch_all($allRatingsForThisProduct);

    if (count($allRatingsForThisProductArray) === 0) {
        $ratingAverage = 0;
    } else {
        $sum = 0;
        foreach ($allRatingsForThisProductArray as $elem) {
            $sum += $elem[0];
        }
        $ratingAverage = $sum / count($allRatingsForThisProductArray);
    }
    queryMysql("UPDATE product SET averageRating='$ratingAverage' WHERE id='$productID'");
}

/**
 * Creates a star rating scale. (★☆☆☆☆)
 *
 * @param $rating
 * @return void
 */
function createRatingScale($rating) {
    for ($k = 1; $k <= 5; $k++) {
        if ($k <= $rating) {
            echo "<span class='fa fa-star checked'></span>";
        } else {
            echo "<span class='fa fa-star'></span>";
        }
    }
}


/**
 * Returns a json out of all orders.
 *
 * @param $orders
 * @return false|string
 */
function createOrdersJSON($orders) {
    $sumOrdersTotal = 0;
    $sumOrdersYear = 0;
    $sumOrdersMonth = 0;
    $sumOrdersDay = 0;

    $oldYear = "";
    $oldMonth = "";
    $oldDay = "";

    $ordersJSON = [];
    $yearJSON = [];
    $monthJSON = [];
    $dayJSON = [];


    foreach ($orders as $order) {

        $orderYear = substr($order[1], 0, 4);
        $orderMonth = substr($order[1], 5, 2);
        $orderDay = substr($order[1], 8);

        $sumOrdersTotal += $order[0];

        if ($oldYear === "" || $orderYear === $oldYear) {

            $sumOrdersYear += $order[0];

            if ($oldMonth === "" || $orderMonth === $oldMonth) {

                $sumOrdersMonth += $order[0];

                if ($oldDay === "" || $orderDay === $oldDay) {

                    $sumOrdersDay += $order[0];

                } else {
                    $dayJSON[] = array("day" => $oldDay, "ordersTotal" => $sumOrdersDay);

                    $sumOrdersDay = 0;
                    $sumOrdersDay += $order[0];
                }

            } else {
                $dayJSON[] = array("day" => $oldDay, "ordersTotal" => $sumOrdersDay);

                $monthJSON[] = array("month" => $oldMonth, "ordersTotal" => $sumOrdersMonth, "dailyOrders" => $dayJSON);
                $sumOrdersMonth = 0;
                $sumOrdersMonth += $order[0];
                $sumOrdersDay = 0;
                $sumOrdersDay += $order[0];
                $dayJSON = [];

            }

        } else {
            $dayJSON[] = array("day" => $oldDay, "ordersTotal" => $sumOrdersDay);
            $monthJSON[] = array("month" => $oldMonth, "ordersTotal" => $sumOrdersMonth, "dailyOrders" => $dayJSON);
            $yearJSON[] = array("year" => $oldYear, "ordersTotal" => $sumOrdersYear, "monthlyOrders" => $monthJSON);
            $sumOrdersYear = 0;
            $sumOrdersYear += $order[0];
            $sumOrdersMonth = 0;
            $sumOrdersMonth += $order[0];
            $monthJSON = [];
            $sumOrdersDay = 0;
            $sumOrdersDay += $order[0];
            $dayJSON = [];
        }

        $oldDay = $orderDay;
        $oldMonth = $orderMonth;
        $oldYear = $orderYear;

    }
    $dayJSON[] = array("day" => $oldDay, "ordersTotal" => $sumOrdersDay);
    $monthJSON[] = array("month" => $oldMonth, "ordersTotal" => $sumOrdersMonth, "dailyOrders" => $dayJSON);
    $yearJSON[] = array("year" => $oldYear, "ordersTotal" => $sumOrdersYear, "monthlyOrders" => $monthJSON);
    $ordersJSON[] = array("ordersTotal" => $sumOrdersTotal, "annualOrders" => $yearJSON);


    return json_encode($ordersJSON);
}

session_start();
session_timeout();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>WebShop</title>

        <!-- AUTHOR-STYLESHEET -->
        <link rel="stylesheet" type="text/css" href="css/styles.css">

        <!-- FONTAWESOME-STYLESHEET -->
        <script src="https://kit.fontawesome.com/eae3c11f82.js" crossorigin="anonymous"></script>

    </head>