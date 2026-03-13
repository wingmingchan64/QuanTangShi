<?php
const 詩題 = '詩題';
const 副題 = '副題';
const 作者 = '作者';

function build_tree_corpus(
    string $txt_dir,
    string $tree_dir,
    array $group_map
): void
{
    if ( !is_dir( $tree_dir ) )
    {
        mkdir( tree_dir, 0777, true );
    }

    $files = glob( $txt_dir . '/*.txt' );

    foreach ( $files as $file )
    {
        $txt = file_get_contents( $file );
        $lines = preg_split("/\R/u", $txt);
		
        if ( !$lines )
        {
            continue;
        }

		// first line is 424-001 白居易 賀雨
        $header = trim( $lines[ 0 ] );
        $header_parts = 
			preg_split( '/\s+/u', $header, 3 );
        $doc_id = $header_parts[ 0 ] ?? '';

        if ($doc_id === '')
        {
            continue;
        }

        // 判斷是否組詩
        if ( isset( $group_map[ $doc_id ] ) )
        {
            write_member_tree_files(
                $txt,
                $group_map,
                $tree_dir
            );
        }
        else
        {
            write_single_tree_file(
                $txt,
                $tree_dir
            );
        }

        echo "Processed: $doc_id\n";
    }
}

function line_to_sentence_tree( string $line ): array
{
    $result = [];

    $sentences = array_values( array_filter(
        explode( '。', trim( $line ) ),
        fn( $s ) => $s !== ''
    ) );

    foreach( $sentences as $sent_idx => $sentence )
    {
        $sent_key = ( string )( $sent_idx + 1 );
        $result[ $sent_key ] = [];

        $chars = preg_split( 
			'//u', $sentence, -1, PREG_SPLIT_NO_EMPTY );

        foreach( $chars as $char_idx => $ch )
        {
            $result[ $sent_key ][( string )( $char_idx + 1 )] = $ch;
        }
    }

    return $result;
}

function write_member_tree_files(
	string $txt, 
	array $group_map, 
	string $output_dir ): array
{
    $lines = preg_split( "/\R/u", $txt );

    if ( !$lines || count( $lines ) === 0 )
    {
        return [];
    }

    $header = trim( $lines[ 0 ] );
    $header_parts = preg_split( '/\s+/u', $header, 3 );
    $doc_id = $header_parts[ 0 ] ?? '';

    if ( $doc_id === '' || !isset(
		$group_map[ $doc_id ] ) )
    {
        return [];
    }

    $info = $group_map[$doc_id];
    $subtitle_lines = [];

    foreach( $info as $poem_no => $value )
    {
		// 組詩樹沒有詩題
        if ( $poem_no === 詩題 )
        {
            continue;
        }

		// 副題
        foreach( $value as $line_no => $subtitle )
        {
			print_r( $value );
            $subtitle_lines[ (int)$poem_no ] = [
                'line' => (int)$line_no,
                'subtitle' => $subtitle
            ];
        }
    }

    ksort( $subtitle_lines );

    $poem_numbers = array_keys( $subtitle_lines );
    $poem_count = count( $poem_numbers );

    $ranges = [];

    for( $i = 0; $i < $poem_count; $i++ )
    {
        $poem_no = $poem_numbers[ $i ];
        $start = $subtitle_lines[ $poem_no ][ 'line' ] + 1;

        if( $i + 1 < $poem_count )
        {
            $next = $poem_numbers[ $i + 1 ];
            $end = $subtitle_lines[ $next][ 'line' ] - 1;
        }
        else
        {
            $end = count( $lines );
        }

        $member_doc_id = $doc_id . '-' . $poem_no;

        $ranges[ $member_doc_id ] = [
            'start' => $start,
            'end' => $end,
            'subtitle' => 
				$subtitle_lines[ $poem_no ][ 'subtitle' ]
        ];
    }

    $trees = [];

    foreach( $ranges as $member_doc_id => $r )
    {
        $trees[$member_doc_id] = [
            副題 => $r[ 'subtitle' ]
        ];
    }

    for( $i = 1; $i < count($lines); $i++ )
    {
        $line_no = $i + 1;
        $line = trim( $lines[$i] );

        if( $line === '' )
        {
            continue;
        }

        foreach( $ranges as $member_doc_id => $r )
        {
            if($line_no < $r['start'] || $line_no > $r['end'] )
            {
                continue;
            }

            if( $line === $r['subtitle'] )
            {
                continue;
            }

            $trees[ $member_doc_id ][ (string)$line_no ] =
                line_to_sentence_tree( $line );

            break;
        }
    }

    if(!is_dir($output_dir))
    {
        mkdir($output_dir, 0777, true);
    }

    $written = [];

    foreach( $trees as $member_doc_id => $tree )
    {
        $path = $output_dir . '/' . $member_doc_id . '.json';

        file_put_contents(
            $path,
            json_encode(
				$tree, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        $written[] = $path;
    }

    return $written;
}

function write_single_tree_file(
	string $txt, 
	string $output_dir): ?string
{
    $lines = preg_split( "/\R/u", $txt );

    if ( !$lines || count( $lines ) === 0 )
    {
        return null;
    }

    $header = trim( $lines[0] );
    $header_parts = preg_split( '/\s+/u', $header, 3 );

	// first line is 424-001 白居易 賀雨
    $doc_id = $header_parts[ 0 ] ?? '';
	$author = $header_parts[ 1 ] ?? '';
    $title = $header_parts[ 2 ] ?? '';

    if( $doc_id === '' )
    {
        return null;
    }

    $tree = [
		作者 => $author,
        詩題 => $title
    ];

    for( $i = 1; $i < count($lines); $i++ )
    {
        $line_no = $i + 1;
        $line = trim( $lines[$i] );

        if( $line === '' )
        {
            continue;
        }

        $tree[(string)$line_no] = line_to_sentence_tree($line);
    }

    if( !is_dir($output_dir ) )
    {
        mkdir($output_dir, 0777, true);
    }

    $path = $output_dir . '/' . $doc_id . '.json';

    file_put_contents(
        $path,
        json_encode(
			$tree, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );

    return $path;
}
?>
