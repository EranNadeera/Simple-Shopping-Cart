<?php
    session_start();
    $db_name = "shopping";
    $connection = mysqli_connect("localhost","root","",$db_name);

    // add to cart items
    if(isset($_POST["add"])){
        if(isset($_SESSION["shopping_cart"])){
            $item_array_id = array_column($_SESSION["shopping_cart"],"product_id");
            if(!in_array($_GET["id"], $item_array_id)){
                $count = count($_SESSION["shopping_cart"]);
                $item_array = array(
                    'product_id' => $_GET["id"],
                    'product_name' => $_POST["hidden_name"],
                    'product_price' => $_POST["hidden_price"],
                    'product_quantity' => $_POST["quantity"],
                );
                $_SESSION["shopping_cart"][$count] = $item_array;
                echo '<script>window.location="index.php"</script>';
            }else{
                echo '<script>alert("Product is already in the cart")</script>';
                echo '<script>window.location="index.php"</script>';
            }
        }else{
            $item_array = array(
                'product_id' => $_GET["id"],
                'product_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'product_quantity' => $_POST["quantity"],
            );
            $_SESSION["shopping_cart"][0] = $item_array;
        }
    }

    //remove items
    if(isset($_GET["action"])){
        if($_GET["action"] == "delete"){
            foreach($_SESSION["shopping_cart"] as $keys => $value){
                if($value["product_id"] == $_GET["id"]){
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>alert("Product has been Removed")</script>';
                    echo '<script>window.location="index.php"</script>';
                }
            }
        }
    }

    
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        .product{
            border: 1px solid #eaeaec;
            margin: 2px 2px 2px 2px;
            padding: 10px;
            text-align: center;
            background-color: #efefef;
        }
        table, th, tr{
            text-align: center;
        }
        .title2{
            text-align: center;
            color: #66afe9;
            background-color: #efefef;
            padding: 2%;
        }
        h2{
            text-align: center;
            color: #66afe9;
            background-color: #efefef;
            padding: 2%;
        }
        table th{
            background-color: #efefef;
        }
    </style>
  </head>
  <body>
    <div class="container" style="width: 65%">
        <h2>Shopping Cart</h2>
        <?php
            $query = "SELECT * FROM product ORDER BY id ASC";
            $result = mysqli_query($connection, $query);
            if(mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)){
                    ?>
                        <div class="col-md-3" style="float: left;">
                            <form action="index.php?action=add&id=<?php echo $row["id"];?>" method="post">
                                <div class="product">
                                    <img src="<?php echo $row["image"]; ?>" width="190px" height="200px" class="img-responsive">
                                    <h5 class="text-info"><?php echo $row["description"];?></h5>
                                    <h5 class="text-danger"><?php echo $row["price"];?></h5>
                                    <input type="text" name="quantity" class="form-control" value="1">
                                    <input type="hidden" name="hidden_name" value="<?php echo $row["description"];?>">
                                    <input type="hidden" name="hidden_price" value="<?php echo $row["price"];?>">
                                    <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart">
                                </div>
                            </form>
                        </div>
                    <?php
                }
            }  
        ?>

        <!-- add cart details -->
        <div style="clear: both"></div>
        <h3 class="title2">Shopping Cart Details</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Product Description</th>
                    <th width="10%">Quantity</th>
                    <th width="13%">Price Details</th>
                    <th width="10%">Total Price</th>
                    <th width="17%">Remove Item</th>
                </tr>

                <?php
                    if(!empty($_SESSION["shopping_cart"])){
                        $total=0;
                        foreach($_SESSION["shopping_cart"] as $key => $value){
                            ?>
                        <tr>
                            <td><?php echo $value["product_name"];?></td>
                            <td><?php echo $value["product_quantity"];?></td>
                            <td><?php echo $value["product_price"];?></td>
                            <td><?php echo number_format($value["product_quantity"]*$value["product_price"],2);?></td>
                            <td><a href="index.php?action=delete&id=<?php echo $value["product_id"];?>"><span class="text-danger">Remove Item</span></a></td>
                        </tr>

                        <?php
                            $total = $total + ($value["product_quantity"]*$value["product_price"]);
                        }
                        ?>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td align="right"><?php echo number_format($total,2);?></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>