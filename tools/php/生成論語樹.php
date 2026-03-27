<?php
/*
php C:\Users\yyang\php\生成論語樹.php
*/
require_once(
	dirname( __DIR__, 1 ) . DIRECTORY_SEPARATOR .
	'Dufu-Analysis' . DIRECTORY_SEPARATOR .
	'tools' . DIRECTORY_SEPARATOR .
	"php" . DIRECTORY_SEPARATOR .
	"lib" . DIRECTORY_SEPARATOR .
	"函式.php" );

require_once( 'functions.php' );
$title = '論語';

$txt = file_get_contents( 
	'C:\Users\yyang\\' . $title . '\canonical_text\\' . 
	'01.txt' );
$tree = build_lunyu_tree( $txt );
$path = 'C:\Users\yyang\\' . $title . '\trees\\' . '01' . 
	'.json';
file_put_contents(
	$path,
	json_encode(
		$tree, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);
print_r( $tree );
?>