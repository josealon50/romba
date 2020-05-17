<?php
namespace Bot\RomSpider;

use Bot\Http\Http;
use Masterminds\HTML5;
    
class RomSpider extends Http{

    protected $maxPenetration = NULL;
    protected $fetchDelay = NULL;
    protected $allowOffsite = NULL;
    
    public function __construct( $url, $maxPenetration, $fetchDelay, $allowOffsite ){
        parent::__construct( $url );
        $this->maxPenetration = $maxPenetration;
        $this->fetchDelay = $fetchDelay;
        $this->allowOffsite = $allowOffsite;
    } 

    public function getMaxPenetration(){
        return $this->maxPenetration;
    }

    public function getFetchDelay(){
        return $this-fetchDelay;
    }

    public function getAllowOffsite(){
        return $this->allowOffsite;
    }

    public function http( $url, $ref, $method, $data_array, $incl_head ){
        return parent::http( $url, 'test', $method, $data_array, '' ); 
    }

    public function return_between( $html, $start, $stop, $type ){
        return parent::return_between( $html, $start, $stop, $type );
    }

    public function harvestLinks( $html ){
        $html5 = new HTML5();  
        $dom = $html5->loadHTML( $html );
        $urls = $dom->getElementsByTagName('a');
        $links = [];
        foreach( $urls as $url ){
            $temp = [];
            $temp[] = trim($url->textContent);
            $temp[] = $url->getAttribute('href');
            $links[] = $temp;
        }
        return $links;
    }

    public function getMaxNumberOfPagination( $pagination ){
        $html5 = new HTML5();  
        $dom = $html5->loadHTML( $pagination );
        $pages = $dom->getElementsByTagName('ul');
        $max = 0;
        foreach ($pages->item(0)->getElementsByTagName('li') as $page) {
            if ( ctype_digit($page->nodeValue) ){
               if ( intval( $page->nodeValue ) > $max ){
                    $max = intval( $page->nodeValue );
                }
            }                
        }
        return $max;

    }

    public function isUrlATarget( $url, $allowedKeywords ){
        foreach( $allowedKeywords as $keyword ){
            if( strpos( $url, $keyword )){
                return true;
            }
        }
        return false;
    }
    public function isValidTargetTitle( $url, $allowedKeywords ){
        foreach( $allowedKeywords as $keyword ){
            if( strpos( $url, $keyword )){
                return true;
            }
        }
        return false;
    }

    public function isDuplicateURL( $query, $table,  $params ){
        $dup =  $query->query( $table, [], $params ); 
        if( count($dup) > 0){ 
            return true;
        }
        return false;
    }

}

