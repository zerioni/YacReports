<!DOCTYPE html>
<?php
require_once "database.php";
require_once "Order.php";
$itemcodes = explode(",", trim($_POST['itemcode'] ?? ''));
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
    
        $orders = new Order($_POST['itemcode']);
        $orders = $orders->getOrderDetails();  

        $total = $orders['total'];
        $product_name = $orders['product_name'];

        if(!empty($orders)){
    ?>
      

<h4><?=$product_name?> | Ticket Total: <?=$total?></h4>
<div id="page-info" style="display:none">
    <?php print_r(json_encode($orders['orders'][0], true)); ?>
    </div>
<table><tbody>
    <tr><th style="text-align:left">Customer Name</th><th>Customer Email</th><th>Ticket Quantity</th><th style='padding-left:15px'>Ticket Price</th><th style='padding-left:15px'>Variant name</th></tr>
<?php
   
    foreach($orders['orders'] as $order) { 
        echo "<tr>
                <td style='min-width:250px'>".$order['address_firstname'] . "&nbsp;" . $order['address_lastname'] ."</td>
                <td>".$order['user_email']."</td>
                <td>".$order['order_product_quantity']."</td>
                <td style='padding-left:15px'>".strstr($order['order_product_price'], '.', true)."</td>
                <td style='padding-left:15px'>".$order['order_product_name']."</td>
                </tr>";
                if(isset($order['custom_fields']) && array_filter($order['custom_fields'])) {
                    echo "<tr><td colspan='5'><strong>Custom Field Responses</strong><br />";
                    foreach($order['custom_fields'] as $key => $value) {
                        if(!empty($value)){
                        echo $key . ": " . $value . "<br />";
                        }
                    }
                    echo "<hr></td></tr>";
                }
                else {
                    echo "<tr><td colspan='5'><hr></td></tr>";
                }
                
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
