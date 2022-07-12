<?php

require_once 'header.php';

echo <<<_END
                            
        <!-- HEADER-BANNER -->

        <header id="header-banner"></header>

        _END;

$email = $error = "";
$feedback = "";

/**
 * Adds e-mails for newsletter.
 */
if (isset($_POST['email'])) {
    $email = sanitizeString($_POST['email']);

    if ($email == "") {
        $error = "style='border: 2px solid red; box-shadow: 0 0 5px 1px red inset'";
    } else {

        $emailAddresses = queryMySQL("SELECT * FROM newsletter WHERE email='$email'");

        if ($emailAddresses->num_rows) {
            $feedback = "<p style='color: red'>This email has already been registered!</p>";
        } else {
            queryMysql("INSERT INTO newsletter VALUES('$email')");
            echo '<script> alert("You have successfully signed up for the newsletter!") </script><meta http-equiv="refresh" content="0, URL=index.php">';
        }
    }
}

echo <<<_END
                <!-- NEWSLETTER-SECTION -->
                
                <section id="newsletter-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <p><b>Sign up for our newsletter now and don't miss anything!</b> We will always send you the latest releases.</p>
                            </div>
                            <div class="col-2 clearfix">
                                <form id="nb-form" action="index.php" method="post">
                                    <input type="email" name="email" value='$email' $error placeholder="Enter your email address here!" pattern="(?:[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*|\\u0022(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\\u0022)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])">
                                    <button class="btn-typ-1" type="submit">Register now!</button>
                                    $feedback
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- PRODUCT-SECTION -->

                <section id="product-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <header class="intro-container">
                                    <h1>WebShop</h1>
                                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                                </header>
                            </div>
                        </div>
        _END;

/**
 * Button to add new products.
 */
if ($isAdmin) {
    echo <<<_END
                        <div class="row">
                            <div class="col-6">
                                <a href="addProduct.php">
                                    <div class="btn-typ-1 add-btn">
                                        <i class="fas fa-plus"></i>                            
                                    </div>
                                </a>
                                <span style="background-color: greenyellow">live</span>
                                <br>
                                <span style="background-color: red">draft</span>
                            </div>
                        </div>
            _END;
}

echo '<div id="article-rows">';

$products = queryMysql("SELECT * FROM product");
$shoppingCartArray = mysqli_fetch_all($products);

$i = 0;

/**
 * Creates product boxes.
 */
foreach ($shoppingCartArray as $product) {
    $statusBorder = "";
    if ($i === 0) {
        echo                '<div class="row">';
    }

    /**
     * Shows which product is live and can be seen by users and which don't.
     */
    if ($product[4] === "LIVE" && $isAdmin) {
        $statusBorder = "style='border: 5px solid greenyellow'";
    } else if ($product[4] === "DRAFT" && $isAdmin) {
        $statusBorder = "style='border: 5px solid red'";
    }
    if ($isAdmin || ($product[4] === "LIVE" && $product[5] !== "0")) {

        echo <<<_END
                                <div class="col-2">
                                    <article class="article-box" $statusBorder>
                                        <h2 class="searchable">$product[1]</h2>
                                        <img class="product-img" src="img/productImg/$product[6]" alt="Kein Bild vorhanden...">
                                        <h3 class="searchable">$product[3]€</h3>
                                        <h4>Description:</h4>
                                        <textarea class="searchable" readonly>$product[2]</textarea>
                                        <h5>Stock: $product[5]</h5>
                                        <p>
                _END;
                                        createRatingScale($product[7]);
        echo <<<_END
                                        </p>
                                        <form action="productReviews.php?id=$product[0]" method="post">
                                            <button class="btn-typ-1 reviews-btn" type="submit">Reviews</button>
                                        </form>
                                        <form action="addToCart.php?id=$product[0]" method="post">
                                            <select class="btn-typ-1 quantitySelect" name="quantity">
                                                <option value="1" selected>1</option>
                _END;
        for ($j = 2; $j <= $product[5]; $j++) {
            echo                                '<option value="' . $j . '">' . $j . '</option>';
        }
        echo <<<_END
                                            </select>        
                                        <button class="btn-typ-1 add-to-cart-btn" type="submit">Add to Cart  <i class="fas fa-cart-plus"></i></button>                          
                                        </form>
                _END;
        if ($isAdmin) {
            echo <<<_END
                                        <form action="editProduct.php?id=$product[0]" method="post">
                                            <button class="btn-typ-1 edit-button" type="submit"><i class="fas fa-cog"></i></button>
                                        </form>
                    _END;
        }
        echo <<<_END
                                </article>
                            </div>
                _END;
    }
    $i++;
    if ($i === 3) {
        echo            '</div>';
        $i = 0;
    }
}
if (count($shoppingCartArray) % 3 != 0) {
    echo            '</div>';
}

echo '</div></section>';

require_once 'footer.html';

?>