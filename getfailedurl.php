<?php
include_once('db.php');
 $result=mysqli_query($con,"SELECT * FROM `product_url`");
 $count=mysqli_num_rows($result);
 echo json_encode($count);


?>