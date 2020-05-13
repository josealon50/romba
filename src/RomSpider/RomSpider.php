<?php
namespace Bot\RomSpider;

use Bot\Http\Http;
    
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
}

