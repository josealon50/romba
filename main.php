<?php
    require "vendor/autoload.php";
    use Bot\Config\Config;
    use Bot\RomSpider\RomSpider;
    use Bot\SQLite\SQLiteConnection; 
    use Bot\SQLite\SQLiteInsert; 
    use Bot\SQLite\SQLiteQuery; 
    use Masterminds\HTML5;

    $sqlite = new SQLiteConnection();
    $insert = new SQLiteInsert($sqlite->connect());
    $query = new SQLiteQuery($sqlite->connect());
    $spider = new RomSpider( Config::TARGET_URL, Config::TARGET_MAX_PENETRATION, Config::TARGET_FETCH_DELAY, Config::TARGET_ALLOW_OFFSITE );
        
    $html = $spider->http( Config::TARGET_URL, 'test', 'GET', [], [] );
    $links = $spider->harvestLinks( $html['FILE'] );
    
    //First we get the top level nvaigation genres links 
    foreach( $links as $link ){
        if ($link[1] != ''){
            if( $spider->isUrLATarget( $link[1], Config::TARGET_KEYWORDS ) && $spider->isValidTargetTitle( $link[0], Config::TARGET_KEYWORDS)  ){
                //check if we already capture the link
                $params = [ 'url' => $link[1], 'system' => $link[0] ];
                if ( !$spider->isDuplicateURL(  $query, 'systems', $params) ){ 
                    $insert->insertSystemURL( $link[0], $link[1] );
                }
            }
        }

    }

    $systems = $query->query( 'systems', ['id', 'system', 'url'], [ 'download' => 1] );
    foreach( $systems as $system ){
        $html = $spider->http( Config::TARGET_URL . $system[2], 'test', 'GET', ['region' => 'USA'], '' );
        $tidy = tidy_parse_string( $html['FILE'] );
        $table = $spider->return_between( $tidy->value, '<table class="table">', "</table>", false );
        $links = $spider->harvestLinks( $table );

        $pagination = $spider->return_between( $tidy->value, '<ul class="pagination__list">', "</ul>", false );
        $max = $spider->getMaxNumberOfPagination( $pagination );
        $i = 0;
        while ( $i <= $max ){
            foreach( $links as $link ){
                if ($link[1] != ''){
                    $params = [ 'system' => $system[0], 'url' => $link[1], 'name' => $link[0] ];
                    if ( !$spider->isDuplicateURL(  $query, 'roms', $params) ){ 
		    	$insertID = $insert->insertRomURL( $system[0], $link[0], $link[1] );

			//Need to parse new html and get download url
            		sleep( Config::TARGET_FETCH_DELAY );
			$html = $spider->http( $link[1], 'test', 'GET', [], '' );
			$tidy = tidy_parse_string( $html['FILE'] );
			$div = $spider->return_between( $tidy->value, '<div class="captcha__success">', "</div>", false );
			$links = $spider->harvestLinks( $div );

			//Url with donwnload capture 
			//need to get actual download url
            		sleep( Config::TARGET_FETCH_DELAY );
			$html = $spider->http( $links[0][1], 'test', 'GET', [], '' );
			$tidy = tidy_parse_string( $html['FILE'] );
			$div = $spider->return_between( $tidy->value, '<div class="wait">', "</div>", false );
			$links = $spider->harvestLinks( $div );
			$params = ['rom_id' => $insertID, 'url' => $links[0][1]];
			if ( !$spider->isDuplicateURL(  $query, 'rom_download_url', $params) ){ 
		  	    $params = ['rom_id' => $insertID, 'url' => $links[0][1]];
			    $insert->insertRomDownloadURL( $insertID, $links[0][1] );
			}
                    }
                }
            }
            $i++;
            sleep( Config::TARGET_FETCH_DELAY );

            $html = $spider->http( Config::TARGET_URL . $system[2] . "/" . $i, 'test', 'GET', ['region' => 'USA'], '' );
            $tidy = tidy_parse_string( $html['FILE'] );
            $table = $spider->return_between( $tidy->value, '<table class="table">', "</table>", false );
            $links = $spider->harvestLinks( $table );
        }
	//UpdupdateDownloadSystemsate download flag for sytem
	$insert->updateDownloadSystems( $system[0] );
	
    }
