<?php
include_once('db.php');
 $result=mysqli_query($con,"SELECT * FROM `product_url`");
 while($urls=mysqli_fetch_array($result))
 {
    $allUrls[]=$urls['url'];
 }

 echo json_encode($allUrls);


?>