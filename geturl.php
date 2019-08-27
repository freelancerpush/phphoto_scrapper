<?php 
include_once('db.php');
/*$pageUrl="https://www.bhphotovideo.com/c/search?setIPP=100&ci=12056&srtclk=itemspp&ipp=1000&N=4232860707";*/
$pageUrl=$_POST['pageUrl'];
        $pageContant = stream_get_contents(fopen($pageUrl, "rb")); 

if(preg_match_all('/<a\s+href=["\']([^"\']+)["\']/i', $pageContant, $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
//echo count($all_hrefs);exit;

foreach($all_hrefs as $href)
{
	if(stripos($href, 'https://') === 0 && stripos(strrev($href), strrev('.html')) === 0) {

		$allUrls[]=$href;
					/*//curl start
    	// set post fields
			$post = ['url'=>$href];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://localhost/string-splitting/product.php');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
			echo $response = curl_exec($ch);
			 
    	   //curl stop*/
		}
}
 
for($i=0;$i<count($allUrls);$i++)
{
  mysqli_query($con,"INSERT INTO `product_url`(`url`)VALUES('".$allUrls[$i]."')");
}
echo json_encode($allUrls);
//echo $i;
exit;
?>
        
        

	