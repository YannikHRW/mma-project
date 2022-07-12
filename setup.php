<!DOCTYPE html>
<html>
    <head>
        <title>Setting up database</title>
    </head>
    <body>

        <h3>Setting up...</h3>

<?php
    require_once 'functions.php';

    createTable('user',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       firstName VARCHAR(16),
                       lastName VARCHAR(16),
                       email VARCHAR(32),
                       pass VARCHAR(32),
                       userType ENUM("USER", "ADMIN") NOT NULL DEFAULT "USER"');

    createTable('newsletter',
                'email VARCHAR(32)');

    createTable('product',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(32),
                       description TEXT,
                       price DOUBLE,
                       status ENUM("DRAFT", "LIVE"),
                       stock INT,
                       imageName VARCHAR(64),
                       averageRating DOUBLE DEFAULT "0"');

    createTable('productReview',
                'userID INT UNSIGNED NOT NULL, FOREIGN KEY (userID) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
                       productID INT UNSIGNED NOT NULL, FOREIGN KEY (productID) REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE, 
                       rating ENUM("0","1","2","3","4","5") NOT NULL DEFAULT "0",
                       comment TEXT');

    createTable('shoppingCart',
                'userID INT UNSIGNED NOT NULL, FOREIGN KEY (userID) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
                       productID INT UNSIGNED NOT NULL, FOREIGN KEY (productID) REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE,
                       quantity INT NOT NULL');

    createTable('orders',
                'userID INT UNSIGNED NOT NULL, FOREIGN KEY (userID) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
                       productID INT UNSIGNED NOT NULL, FOREIGN KEY (productID) REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE,
                       quantity INT,
                       date DATE');

?>

        <br>...done.
    </body>
</html>