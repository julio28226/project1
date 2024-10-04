<?php
  // create short variable names
  $petqty = (int) $_POST['petqty'];
  $toyqty = (int) $_POST['toyqty'];
  $foodqty = (int) $_POST['foodqty'];
  $address = preg_replace('/\t|\R/',' ',$_POST['address']);
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  $date = date('H:i, jS F Y');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Pet Friendly Service - Order Results</title>
  </head>
  <body>
    <h1>Pet Friendly Service</h1>
    <h2>Order Results</h2> 
    <?php
      echo "<p>Order processed at ".date('H:i, jS F Y')."</p>";
      echo "<p>Your order is as follows: </p>";

      $totalqty = 0;
      $totalamount = 0.00;

      define('PETPRICE', 100);
      define('TOYPRICE', 10);
      define('FOODPRICE', 4);

      $totalqty = $petqty + $toyqty + $foodqty;
      echo "<p>Items ordered: ".$totalqty."<br />";

      if ($totalqty == 0) {
        echo "You did not order anything on the previous page!<br />";
      } else {
        if ($petqty > 0) {
          echo htmlspecialchars($petqty).' pet/s<br />';
        }
        if ($toyqty > 0) {
          echo htmlspecialchars($toyqty).' number of toys<br />';
        }
        if ($foodqty > 0) {
          echo htmlspecialchars($foodqty).' bags of food<br />';
        }
      }


      $totalamount = $petqty * PETPRICE
                   + $toyqty * TOYPRICE
                   + $foodqty * FOODPRICE;

      echo "Subtotal: $".number_format($totalamount,2)."<br />";

      $taxrate = 0.10;  // local sales tax is 10%
      $totalamount = $totalamount * (1 + $taxrate);
      echo "Total including tax: $".number_format($totalamount,2)."</p>";

      echo "<p>Pet Sitting Address: ".htmlspecialchars($address)."</p>";

      $outputstring = $date."\t".$petqty." pets \t".$toyqty." toy\t"
                      .$foodqty." bags of food\t\$".$totalamount
                      ."\t". $address."\n";

       // open file for appending
       @$fp = fopen("$document_root/../orders/orders.txt", 'ab');

       if (!$fp) {
         echo "<p><strong> Your order was processed.
               Thank you.</strong></p>";
         exit;
       }

       flock($fp, LOCK_EX);
       fwrite($fp, $outputstring, strlen($outputstring));
       flock($fp, LOCK_UN);
       fclose($fp);

       echo "<p>Order written.</p>";
    ?>
  </body>
</html>

