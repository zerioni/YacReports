<!DOCTYPE html>
<?php
require_once "database.php";
$itemcodes = explode(",",trim($_POST['itemcode']));
$db = new DatabaseTransactions();
?>
<html>
<head>
<title>YAC Event Ticket Reporting</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<style>.padded-div{
    float: none;
    margin: 50px
}
    input, text {margin-left: 10px}</style>
</head>

<body>
    <container><section >
        <div class="padded-div">
<?php
    if(!empty($_POST['itemcode'])) {
        echo "<h2>Event Ticket Sales</h2><form action='index.php' method='post'><input type='hidden' name='itemcode' value='' /><input type='submit' value='New Search' /></form>";
    
        $orders = $db->getOrders($_POST['itemcode']);  

        $total = array_sum(array_column($orders, 'order_product_quantity'));
        $product_name = (isset($orders[0]['order_product_name'])) ? $orders[0]['order_product_name'] : 'Product';

        if(!empty($orders)){
    ?>
      

<h4><?=$product_name?> | Ticket Total: <?=$total?></h4>
<table><tbody>
    <tr><th style="text-align:left">Customer Name</th><th>Customer Email</th><th>Ticket Quantity</th><th style='padding-left:15px'>Ticket Price</th><th style='padding-left:15px'>Variant name</th></tr>
<?php
   
    foreach($orders as $order2) { 
        echo "<tr><td style='min-width:250px'>".$order2['address_firstname'] . "&nbsp;" . $order2['address_lastname'] ."</td><td>".$order2['user_email']."</td><td>".$order2['order_product_quantity']."</td><td style='padding-left:15px'>".strstr($order2['order_product_price'], '.', true)."</td><td style='padding-left:15px'>".$order2['order_product_name']."</td></tr>";
    } 
    ?>
    </tbody> </table>
<?php   
        }
        else {
            echo '<h4>No Results found! Please try a new search above.</h4>';
        }

    }  else { 
        echo "
        <h2>Event Ticket Sales</h2>
        <h4>Search using the Item Code provided by the YAC Operations Coordinator</h4>
        <form name='search' method='post' action='index.php'>
        <label>Item Code: </label><input type='text' name='itemcode' /><br />
        <input type='submit' value='Search' />
        </form>";
    }   ?>
            </div>
    </section> 
 </container>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>

</html>
