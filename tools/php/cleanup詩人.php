<?php
/*
php H:\github\QuanTangShi\tools\php\cleanup詩人.php
 */
require_once( 
	dirname( __DIR__, 3 ) . DIRECTORY_SEPARATOR .
	'Dufu-Analysis' . DIRECTORY_SEPARATOR .
	'tools' . DIRECTORY_SEPARATOR .
	'php' . DIRECTORY_SEPARATOR .
	'lib' . DIRECTORY_SEPARATOR .
	 '函式.php' );

$text_path = __DIR__ .
	DIRECTORY_SEPARATOR .
	'李白.txt';
 
$contents = file_get_contents( $text_path );


$ptn = '/第\d+筆/u';
$contents = preg_replace( $ptn, '', $contents );
$ptn = '/（\X+?）/u';
$contents = preg_replace( $ptn, '', $contents );
$ptn = '/\[\d+?]/u';
$contents = preg_replace( $ptn, '', $contents );
$ptn = '/\[頁]卷,冊....\d+,\d+/u';
$contents = preg_replace( $ptn, '', $contents );

// 加 *
$ptn = '/\R\R\R/u';
$contents = preg_replace( $ptn, "\n*\n", $contents );
$contents = str_replace( '。。', '。', $contents );

$異體字 = json_decode( file_get_contents('H:\github\QuanTangShi\tools\php\異體字.json' ), true );

$keys = array_keys( $異體字 );
$len = mb_strlen( $contents );

foreach( $異體字 as $key => $value )
{
	$contents = str_replace( $key, $value, $contents );
}
file_put_contents( $text_path, $contents );

?>
