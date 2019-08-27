<?php 
include_once('db.php');
$pageUrl=$_POST['url'];
//$pageUrl='https://www.bhphotovideo.com/c/product/1333228-REG/sony_ilce_9_b_alpha_a9_mirrorless_digital.html';
    if(fopen($pageUrl, "rb"))
    {
		$pageContant =stream_get_contents(fopen($pageUrl, "rb")); 
      //for brand name
      preg_match_all('/<span itemprop=\"brand\">(.*?)<\/span>/s',$pageContant,$brandmates);
      
         $brand=strip_tags(@$brandmates[0][0]);
      //for product name
       preg_match_all('/<span itemprop=\"name\">(.*?)<\/span>/s',$pageContant,$productNamemates);
       
      $productName=strip_tags(@$productNamemates[0][0]);
      //for B&M code
           preg_match_all('/<span class=\"fs16\ c28\" data-selenium=\"bhSku\">(.*?)<\/span>/s',$pageContant,$BMCodemates);
       
            $BMCode=strip_tags(@$BMCodemates[0][0]);
            $BMCode=preg_replace("/&nbsp;/",'',$BMCode);
            $BMCodeArray=explode('#', $BMCode);
            $BMCode=$BMCodeArray[1];
              
           
        //for MFR code
           preg_match_all('/<span class=\"fs16 c28 mfr-number\">(.*?)<\/span>/s',$pageContant,$MFRCodemates);
             
        $MFRCode=strip_tags(@$MFRCodemates[0][0]);
        $MFRCode=preg_replace("/&nbsp;/",'',$MFRCode);
        $MFRCodeArray=explode('#', $MFRCode);
        $MFRCode=$MFRCodeArray[1];
         
        //for price
           preg_match_all('/<div class=\"pPrice\" data-selenium=\"ProductPrice\">(.*?)<\/p>/s',$pageContant,$pricemates);
       
         $price=strip_tags(@$pricemates[0][0]);
         $price=preg_replace('/\s+/', '', $price);
         $priceArray=explode(':', $price);
         $price=preg_replace("/[^0-9,.]/", "", @$priceArray[1]);
         $price=str_replace(',','',$price);
         $updated_price= (30 / 100) * $price;
         $updated_price=$price+$updated_price;
         
         //for upc
           preg_match_all('/<span class=\"upcNum\">(.*?)<\/span>/s',$pageContant,$upcmates);
       
          $upc=strip_tags(@$upcmates[0][0]);
          $upc=preg_replace("/[^0-9]/", "", $upc);
          
          //for Specification
           /*preg_match_all('/<div data-selenium='."'specWrapper'".'class=\"specWrapper\">(.*?)<\/div>/s',$pageContant,$specificationmates);
       
          $specification=@$specificationmates[0][0];*/

          //for overview
           /*preg_match_all('/<div class=\"ov-desc\">(.*?)<\/div>/s',$pageContant,$overviewmates1);
       
           $overview1=strip_tags(@$overviewmates1[0][0]);
           $overview1=trim($overview1);*/
          
            
          /*preg_match_all('/<div class=\"sectionHeaders js-hiddenFeatures\" data-selenium=\"sectionHeaders\">(.*?)<\/div>/s',$pageContant,$overviewmates2);
       
           $overview2=@$overviewmates2[0][0];*/

          //for product image
          // read all image tags into an array
			preg_match_all('/<img[^>]+>/i',$pageContant, $imgTags); 

			//print_r($imgTags);exit;

			for ($i = 0; $i < count($imgTags[0]); $i++) {
			    // get the source string
			    if (strpos($imgTags[0][$i], 'class="main-image"') !== false) {
			  preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);

			  // remove opening 'src=' tag, can`t get the regex right
			  $origImageSrc = str_ireplace( 'src="', '',  @$imgage[0]);
			   } 
			  
			}
		    $productMainImage=htmlentities(@$origImageSrc);




/*$imagecontent = DownloadImageFromUrl($productMainImage);
$savefile = fopen('myimage.png', 'w');
fwrite($savefile, $imagecontent);
fclose($savefile);*/


$filenameIn = $productMainImage;
$imageName=time().basename($filenameIn);
$filenameOut = (__DIR__ . '/images/' .$imageName);
$contentOrFalseOnFailure   = file_get_contents($filenameIn);
$byteCountOrFalseOnFailure = file_put_contents($filenameOut, $contentOrFalseOnFailure);

   
     
      
      /*$sql="INSERT INTO `info`(`product_url`,`name`,`brand`,`price`,`b_m_code`,`mfr_code`,`upc`,`image`,`specification`,`overview1`,`overview2`,image_name)VALUES('".$pageUrl."','".trim($productName)."','".trim($brand)."','".trim($price)."','".trim($BMCode)."','".trim($MFRCode)."','".trim($upc)."','".trim($productMainImage)."','".mysqli_real_escape_string($con,$specification)."','".mysqli_real_escape_string($con,$overview1)."','".mysqli_real_escape_string($con,$overview2)."','".$imageName."')";*/
      $sql="INSERT INTO `info`(`product_url`,`name`,`brand`,`price`,`updated_price`,`b_m_code`,`mfr_code`,`upc`,`image`,image_name)VALUES('".$pageUrl."','".trim($productName)."','".trim($brand)."','".trim($price)."','".trim($updated_price)."','".trim($BMCode)."','".trim($MFRCode)."','".trim($upc)."','".trim($productMainImage)."','".$imageName."')";
      $rs = mysqli_query($con, $sql);
      if($rs){
        $rs=1;
        mysqli_query($con,"DELETE FROM `product_url`
WHERE `url`='".$pageUrl."'");
      }else{
        $rs=0;
        mysqli_query($con,'INSERT INTO `failed_product`(`url`)VALUES("'.$pageUrl.'")');
     }
      
      echo json_encode($rs);
     }
     else
     {
          mysqli_query($con,'INSERT INTO `failed_product`(`url`)VALUES("'.$pageUrl.'")');
     }


 
exit;









	    
?>
