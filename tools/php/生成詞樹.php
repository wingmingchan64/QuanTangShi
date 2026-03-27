<?php
/*
php C:\Users\yyang\php\test_ci_tree.php
*/
require_once(
	dirname( __DIR__, 1 ) . DIRECTORY_SEPARATOR .
	'Dufu-Analysis' . DIRECTORY_SEPARATOR .
	'tools' . DIRECTORY_SEPARATOR .
	"php" . DIRECTORY_SEPARATOR .
	"lib" . DIRECTORY_SEPARATOR .
	"函式.php" );

require_once( 'functions.php' );
$title = '蘇軾_水調歌頭';

$txt = file_get_contents( 
	'C:\Users\yyang\宋詞三百首\canonical_text\\' . '蘇軾_水調歌頭' . 
	'.txt' );
$tree = build_ci_tree( $txt );
$path = 'C:\Users\yyang\宋詞三百首\trees\\' . '蘇軾_水調歌頭' . 
	'.json';
file_put_contents(
	$path,
	json_encode(
		$tree, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

?>