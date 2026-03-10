<?php
/*
 * php H:\github\QuanTangShi\tools\php\生成空文檔.php
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
 
for( $i = 1; $i<=900; $i++ )
{
	$文檔碼 = str_pad( $i, 3, '0', STR_PAD_LEFT );
	file_put_contents( $text_path . $文檔碼 . '.txt',
		'' );
}
/*
$卷 = '';
$數 = 57;

$text_path = dirname( __DIR__, 2 ) .
	DIRECTORY_SEPARATOR .
	'canonical_txt' . DIRECTORY_SEPARATOR;

for( $i = 1; $i<=$數; $i++ )
{
	$文檔碼 = str_pad( $卷, 3, '0', STR_PAD_LEFT ) . 
		'-' . str_pad( $i, 3, '0', STR_PAD_LEFT );
	$文檔名 = "${文檔碼}.txt";
	
}
*/
?>
