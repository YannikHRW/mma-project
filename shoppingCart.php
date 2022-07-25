<?php

require_once 'functions.php';
require_once 'header.php';

echo <<<_END
            <script>
        
                /**
                * Sets the color of the selected nav-element.
                * @type {HTMLCollectionOf<Element>}
                */
                let navShoppingCarts = document.getElementsByClassName("nav-shopping-cart");
                for (const navShoppingCart of navShoppingCarts) {
                  navShoppingCart.classList.add("active");
                }
                
                /**
                * makes the searchbar invisible.
                * @type {HTMLCollectionOf<Element>}
                */
                let searchInputs = document.getElementsByClassName("search-article-input");
                for (const searchInput of searchInputs) {
                  searchInput.classList.add("invisible");
                }
        
            </script>
            
            <!-- HEADER-BANNER -->

            <header id="header-banner-small"></header>

            <!-- SHOPPING-SECTION -->

                <section id="shopping-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <header class="intro-container">
                                    <h1>Shopping cart</h1>
                                </header>
                            </div>
                        </div>
    _END;

checkIfLoggedIn();

$id = $_SESSION['userID'];

$productsInCart = queryMysql("SELECT productID, quantity FROM shoppingCart WHERE userID='$id'");
$productsInCartArray = mysqli_fetch_all($productsInCart);
$sumStock = 0;
$sumPrice = 0;


/**
 * Creates the product boxes with price and quantity.
 */
foreach ($productsInCartArray as $cartProduct) {

    echo "<div class='row'><div class='col-6'><div class='small-box'>";

    $cartProductID = $cartProduct[0];
    $products = queryMysql("SELECT * FROM product WHERE id='$cartProductID'");
    $productsArray = mysqli_fetch_all($products);
    $productArray = $productsArray[0];

    echo <<<_END
                          <h2>$productArray[1]</h2>
                          <img class='product-img' src='img/productImg/$productArray[6]' alt='Kein Bild vorhanden...'>
                          <h4>Price: <span class="product-price">$productArray[3]</span> €</h4>
                          <h4>Description</h4>
                          <textarea readonly>$productArray[2]</textarea>
                          <h4>Quantity:</h4>
                          <form class="shopping-cart-form" action="addToCart.php?id=$productArray[0]" method="post">
                              <div class="quantity-div">
                                 <select class='btn-typ-1 quantity-select-cart' name='quantity'>
            _END;

    /**
     * Selects and displays the quantity in the cart of a product.
     */
    for ($j = 0; $j <= $productArray[5]; $j++) {
        $selected = "";
        if ($j == $cartProduct[1]) {
            $selected = "selected";
        }
        echo                                '<option value="' . $j . '"' . " " . $selected . '>' . $j . '</option>';
    }

    $sumPriceThisProduct = $productArray[3] * $cartProduct[1];

    echo <<<_END
                                </select>
                              </div>
                          </form>
                          <p>Sum: <strong>$sumPriceThisProduct €</strong></p>
                       </div>
                   </div>
               </div>
            _END;

    $sumPriceThisProduct = 0;
    $sumStock += $cartProduct[1];
    $sumPrice += $productArray[3] * $cartProduct[1];
}


$sumStock = $sumStock . "";

/**
 * Creates a sum box.
 */
echo <<<_END
            <div class='row'>
                <div class='col-6'>
                    <div class='small-box' id='input-box-sum'>
                        Sum ($sumStock articles): <strong>$sumPrice €</strong>
                        <form action='orderProducts.php' method='post'>
                            <div>
                                <button class='btn-typ-1' type='submit'>Purchase product/s</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        _END;

require_once 'footer.html';

?>