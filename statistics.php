<?php

require_once 'functions.php';
require_once 'header.php';

echo <<<_END
        
             <script>
            
                /**
                * Sets the color of the selected nav-element.
                * @type {HTMLCollectionOf<Element>}
                */
                let navStatistics = document.getElementsByClassName("nav-statistics");
                for (const navStatistic of navStatistics) {
                  navStatistic.classList.add("active");
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

        _END;

$orders = queryMysql("SELECT quantity, date FROM orders");
$orders = mysqli_fetch_all($orders);
$ordersJSON = createOrdersJSON($orders);

file_put_contents("statistics/orderStatistics.json", $ordersJSON);

?>
        <section id="statistics-section">
            <div class="container">

                <div class="row">
                    <div class="col-6">
                        <header class="intro-container">
                            <h1>Orders overview</h1>
                            <input id='month-select' type='month'>
                        </header>
                    </div>
                </div>
<?php
checkIfUserIsAdmin();
?>
                <div class="row">
                    <div class="col-6">
                        <canvas id="myCanvas" height="450" width="1220"></canvas>
                    </div>
                </div>

            </div>
        </section>

    <script type="text/javascript" src="js/statistics.js"></script>

<?php
require_once 'footer.html';
?>