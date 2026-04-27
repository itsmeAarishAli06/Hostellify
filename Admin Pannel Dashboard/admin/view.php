<a href="cart.php">Add Item</a>

<table width="100%" cellspacing="0" cellpadding="20"  border=1>
    <tr>
        <th>Name</th>
        <th>RPice</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>

    <?php
    session_start(); 
        foreach($_SESSION['cart'] as $cart){

        echo '
                <tr>
                    <td>'.$cart['name'].'</td>
                    <td>'.$cart['price'].'</td>
                    <td>'.$cart['qty'].'</td>
                    <td>'.$cart['qty'] * $cart['price'].'</td>
                </tr>
        ';

        }
    ?>

</table>