<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

checkIfUserIsAdmin();

echo "<div class='row'><div class='col-6'><div class='small-box'>";

$title = $description = $price = $stock = $error = "";

if (isset($_POST['title'])) {

    $title = sanitizeString($_POST['title']);
    $description = sanitizeString($_POST['description']);
    $status = sanitizeString($_POST['status']);
    $price = sanitizeString($_POST['price']);
    $stock = sanitizeString($_POST['stock']);

    if ($title == "" || $description == "" || $price == "" || $stock == "") {

        $error = "<br><span class='error'>Not all fields were entered!</span>";

    } else {

        $productTitles = queryMySQL("SELECT title FROM product WHERE title='$title'");

        if ($productTitles->num_rows) {

            $error = "<span class='error'>Product already exists!</span><br><br>";

        } else {

            $imageName = $_FILES['upload-file']['name'];
            validateFile($imageName, "addProduct.php");

            queryMysql("INSERT INTO product VALUES(NULL, '$title', '$description', '$price', '$status', '$stock', '$imageName', '0')");
            die("<h4>Product created</h4>Back to <a href='index.php'>Start</a><br>next <a href='addProduct.php'>Product</a></div></div></div></div></section>");
        }
    }
}

echo <<<_END
            <h1>Add a new Product</h1>
            
            <form class="change/create-form" action="addProduct.php" method="post" enctype="multipart/form-data">$error
        
                <div>
                    <label for="input-title">Title:</label>
                    <input id="input-title" type="text" maxlength="32" name='title' placeholder="Title" value='$title'>
                </div>
                         
                <div>
                    <label for="input-description">Description:</label>
                    <textarea id="input-description" name='description' placeholder="Description...">$description</textarea>
                </div>
        
                <div>
                    <label for="input-price">Price:</label>
                    <input id="input-price" type="number" name='price' maxlength="11" step="0.01" placeholder="Price" value="$price">
                </div>
        
                <div>
                    <input type="radio" name='status' value='DRAFT' checked>Draft
                    <input type="radio" name='status' value='LIVE'>Live
                </div>
        
                <div>
                    <label for="input-stock">Stock:</label>
                    <input id="input-stock" type="number" name='stock' maxlength="11" placeholder="Stock" value="$stock">
                </div>
        
       _END;
?>
                <div>
                    <label for="upload-file">Image (.jpg or .png):</label>
                    <input id="upload-file" type="file" name='upload-file' accept=".jpg, .png">
                </div>

                <div>
                    <button class="btn-typ-1" type="submit">Add Product!</button>
                </div>
                <div>
                    <button class="btn-typ-1" id="reset-btn" type="reset">Reset input!</button>
                </div>

            </form>

        </div>
        </div>
        </div>
    </section>

    <script type="text/javascript" src="js/main.js"></script>
    </body>
</html>