<?php

require_once 'functions.php';


$sumCart = "";
$isLoggedIn = isLoggedIn();
$isAdmin = isAdmin();

if (isset($_SESSION['firstName'])) {
    $userID = $_SESSION['userID'];

    /**
     * Sums the shopping cart quantity for visualizing in nav-bar if user is logged in.
     */
    $shoppingCart = queryMysql("SELECT quantity FROM shoppingCart WHERE userID='$userID'");
    $shoppingCartArray = mysqli_fetch_all($shoppingCart);
    $sum = 0;
    foreach ($shoppingCartArray as $prodQuant) {
        $sum += $prodQuant[0];
    }
    if ($sum > 99) {
        $sumCart = 99 . "+";
    } else {
        $sumCart = $sum . "";
    }

}

echo <<<_END
            <body>
            
                <!-- NAVIGATION BAR -->

                <nav id="header-nav">

                <!-- DESKTOP-NAV -->

                <div class="container" id="desktop-nav">
                    <div class="row">
                        <div class="col-6">

                            <a class="logo-link" href="index.php">
                                <img class="webshop-logo-header" src="img/Webshop-bold.png" alt="Logo vom WebShop">
                            </a>

                            <div class="search-article-div">
                                <input class="search-article-input" type="search" placeholder="Search...">
                            </div>
                            
                            <ul>
            _END;
if ($isLoggedIn) {
    echo <<<_END
                                <li>
                                    <a href="logout.php">
                                        <span>
                                            <span class="nav-second-break">Logout</span>
                                            <i class="fas fa-user" style="color: #A62C21"></i>
                                        </span>
                                    </a>
                                </li>
            _END;
} else {
    echo <<<_END
                                <li>
                                    <a href="login.php">
                                        <span>
                                            <span class="nav-second-break">Login</span>
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </a>
                                </li>
            _END;
}
echo <<<_END
                                <li>
                                    <a class="nav-reviews" href="userReviews.php">
                                        <span>
                                            <span class="nav-first-break">Reviews</span>
                                            <i class="fas fa-star"></i>
                                        </span>
                                    </a>
                                </li>
        _END;
if ($isAdmin) {
    echo <<<_END
                                <li>
                                    <a class="nav-statistics" href="statistics.php">
                                        <span>
                                            <span class="nav-second-break">Statistics</span>
                                            <i class="fas fa-chart-bar"></i>    
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-user-list" href="userList.php">
                                        <span>
                                            <span class="nav-first-break">User list</span>
                                            <i class="fas fa-address-book"></i>    
                                        </span>
                                    </a>
                                </li>
            _END;
} else {
    echo <<<_END
                                <li>
                                    <a class="nav-orders" href="orders.php">
                                        <span>
                                            <span class="nav-second-break">Orders</span>
                                            <i class="fas fa-truck-loading"></i> 
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-shopping-cart" href="shoppingCart.php">
                                        <span>
                                            <span class="nav-first-break">Shopping cart</span>
                                            <i class="fas fa-shopping-cart"></i>$sumCart
                                        </span>
                                    </a>
                                </li>
            _END;
}
echo <<<_END
                            </ul> 
                        </div>
                    </div>
                </div>

                <!-- MOBIlE-NAVIGATION -->

                <div class="container" id="mobile-nav">
                    <div class="row">
                        <div class="col-6">

                            <a class="logo-link" href="index.php">
                                <img class="webshop-logo-header" src="img/Webshop-mobile.png" alt="Logo vom WebShop">
                            </a>

                            <div class="search-article-div">
                                <input class="search-article-input" type="search" placeholder="Search...">
                            </div>

                            <div id="mobile-nav-dropdown">
                                <div id="mobile-nav-button"><span>&equiv;</span></div>
                                <div id="mobile-nav-content">
                                    <ul>
        _END;
if ($isLoggedIn) {
    echo <<<_END
                                        <li>
                                            <a href="logout.php">                                            
                                                Logout
                                            </a>
                                        </li>
            _END;
} else {
    echo <<<_END
                                        <li>
                                            <a href="login.php">
                                                Login
                                            </a>
                                        </li>
            _END;
}
echo <<<_END
                                        <li>
                                            <a class="nav-reviews" href="userReviews.php">
                                                Reviews
                                            </a>
                                        </li>
        _END;
if ($isAdmin) {
    echo <<<_END
                                        <li>
                                            <a class="nav-statistics" href="statistics.php">
                                                Statistics  
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-user-list" href="userList.php">
                                                User list
                                            </a>
                                        </li>
            _END;
} else {
    echo <<<_END
                                        <li>
                                            <a class="nav-orders" href="orders.php">
                                                Orders
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-shopping-cart" href="shoppingCart.php">
                                                Cart
                                            </a>
                                        </li>
            _END;
}
echo <<<_END
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            _END;

