<?php
namespace Bot\Config;

class Config {

    const PATH_TO_SQLITE_FILE = '/home/pi/bots/romba/db/bot.db';
    const TARGET_URL  = 'https://romsdownload.net';
    const TARGET_MAX_PENETRATION = 1;
    const TARGET_FETCH_DELAY = 1;
    const TARGET_ALLOW_OFFSITE = FALSE;

    const TARGET_KEYWORDS = [ 'ROMS', 'roms', 'ROM', 'rom', 'ISOs', 'ISO', 'isos', 'iso' ];

    const EXCL_HEAD =  false;
    const INCL_HEAD =  true;
    const BEFORE = true;
    const AFTER =  false;    

    const EXCL =  true;
    const INCL =  false;
    const COOKIE_FILE = "cookie.txt";

    const HEAD = "HEAD";
    const GET =  "GET";
    const POST = "POST";
    const BOT_NAME = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36';




}

?>
