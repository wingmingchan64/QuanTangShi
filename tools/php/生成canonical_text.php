<?php
/*
php H:\github\QuanTangShi\tools\php\生成canonical_text.php
 */
require_once( 
	dirname( __DIR__, 3 ) . DIRECTORY_SEPARATOR .
	'Dufu-Analysis' . DIRECTORY_SEPARATOR .
	'tools' . DIRECTORY_SEPARATOR .
	'php' . DIRECTORY_SEPARATOR .
	'lib' . DIRECTORY_SEPARATOR .
	 '函式.php' );

$text_path = dirname( __DIR__, 2 ) .
	DIRECTORY_SEPARATOR .
	'raw_txt' . DIRECTORY_SEPARATOR;
 
$卷 = '430';
$作者 = '白居易';

$source_path = dirname( __DIR__, 2 ) .
	DIRECTORY_SEPARATOR .
	'raw_txt' . DIRECTORY_SEPARATOR;
$target_path = dirname( __DIR__, 2 ) .
	DIRECTORY_SEPARATOR .
	'canonical_txt' . DIRECTORY_SEPARATOR;

$contents = file_get_contents(
	$source_path . $卷 . '.txt' );

$parts = explode( "*", $contents );

for( $i = 0; $i<sizeof( $parts ); $i++ )
{
	$文檔碼 = str_pad( $卷, 3, '0', STR_PAD_LEFT ) . 
		'-' . str_pad( $i+1, 3, '0', STR_PAD_LEFT );
	$文檔名 = "${文檔碼}.txt";
	
	$文檔內容 = $文檔碼 . ' ' . $作者 . ' ' . 
		trim( $parts[ $i ] );
	$文檔內容 = str_replace( '，', '。', $文檔內容 );
	file_put_contents( $target_path . $文檔名, $文檔內容 );
}
?>
