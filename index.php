<?php
function vpost($url, $data){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$tmpInfo = curl_exec($curl);
	if (curl_errno($curl)) {
		echo 'Errno'.curl_error($curl);
	}
	curl_close($curl);
	return $tmpInfo;
}
$url = "http://page14.auctions.yahoo.co.jp/jp/auction/s470575105";
$data = "";
$result = vpost($url, $data);
preg_match_all("/<dt class=\"ProductDetail__title\">(.*)<\/dt>/", $result, $matches1);
preg_match_all("/<dd class=\"ProductDetail__description\"><span class=\"ProductDetail__bullet\">：<\/span>(.*)<\/dd>/", $result, $matches2);
//preg_match_all("/<div class=\"ProductImage__inner\">\n(.*)\n<\/div>/", $result, $matches3);
//preg_match_all("/<div class=\"ProductImage js-imageGallery js-disabledContextMenu.*>[\n.]*<!-- \/\.ProductImage -->/", $result, $matches3);
$lines = preg_split("/\n/", $result);
$found = false;
$html_images = "";
foreach($lines as $line)
{
	if (!$found && !preg_match("/<div class=\"ProductImage__body js-imageGallery-body\">/", $line))
		continue;
	if (preg_match("/ProductImage__footer js-imageGallery-footer/", $line))
		break;
	$found = true;
	$html_images .= $line;
}
preg_match_all("/<div class=\"ProductImage js-imageGallery js-disabledContextMenu\">.*<!-- \/\.ProductImage -->/", $result, $matches3);
?>
<!doctype html>
<html>
	<head>
	<meta charset="utf-8">
	<title>Auto reading site page</title>
	</head>
	<body>
	<?php echo print_r($html_images, true); ?>
	<table>
	<?php for($i = 0; $i < count($matches1[0]); $i++) { ?>
	<tr>
		<td>
	<?php echo $matches1[0][$i]; ?>
		</td>
		<td>
	<?php echo $matches2[0][$i]; ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
	</table>
	</body>
</html>