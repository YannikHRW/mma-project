<?php

require_once 'functions.php';
require_once 'headerSecondary.php';

checkIfUserIsAdmin();

echo "<div class='row'><div class='col-6'><div class='small-box'>";

$productID = getIdFromURL();
$productAttributes = queryMysql("SELECT * FROM product WHERE id='$productID'");
$productAttributesArray = mysqli_fetch_row($productAttributes);

$title = $productAttributesArray[1];
$description = $productAttributesArray[2];
$price = $productAttributesArray[3];
$status = $productAttributesArray[4];
$stock = $productAttributesArray[5];
$imageName = $productAttributesArray[6];

$draft = $live = "";
$error = "";

if ($status === "DRAFT") {
    $draft = "checked";
} else {
    $live = "checked";
}


if (isset($_POST['delete'])) {
    queryMysql("DELETE FROM product WHERE id='$productID'");
    echo "<script> alert('Product \"$title\" has been deleted!') </script><meta http-equiv='refresh' content='0, URL=index.php'>";
}

if (isset($_POST['title'])) {

    $title = sanitizeString($_POST['title']);
    $description = sanitizeString($_POST['description']);
    $status = sanitizeString($_POST['status']);
    $price = sanitizeString($_POST['price']);
    $stock = sanitizeString($_POST['stock']);


    if ($title == "" || $description == "" || $price == "" || $stock == "") {

        $error = "<span class='error'>Not all fields were entered!</span>";

    } else {

        $imageName = $_FILES['upload-file']['name'];
        validateFile($imageName, "editProduct.php?id=$productID");

        queryMysql("UPDATE product SET title='$title', description='$description', price='$price', status='$status', stock='$stock', imageName='$imageName' WHERE id='$productID'");
        die("<h4>Product \"$title\" edited!</h4>Back to <p><a href='index.php'>Start</a></p></div></div></div></div></section>");
    }
}

echo <<<_END

            <h1>Edit Product: "$title"</h1>
            
            <form class="change/create-form" action="editProduct.php?id=$productID" method="post" enctype="multipart/form-data">$error
        
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
                    <input type="radio" name='status' value='DRAFT' $draft>Draft
                    <input type="radio" name='status' value='LIVE' $live>Live
                </div>
        
                <div>
                    <label for="input-stock">Stock:</label>
                    <input id="input-stock" type="number" name='stock' maxlength="11" placeholder="Stock" value="$stock">
                </div>     

                <div>
                    <label for="upload-file">Picture (.jpg or .png):</label>
                    <input id="upload-file" value='$imageName' type="file" name='upload-file' accept=".jpg, .png">
                </div>
                
        _END;
?>
                <div>
                    <button class="btn-typ-1 change-btn" type="submit">Edit product!</button>
                </div>
                <div>
                    <button class="btn-typ-1 delete-btn" type="submit" name="delete">Delete product!</button>
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