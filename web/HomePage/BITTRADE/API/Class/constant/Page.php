<?php
/**
* Description of Page
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
class Page {
    const cacheMaxSec = 864000; //10일
    
    const cacheRankingSec = 3600; //1시간
    const cacheStationSec = 300; //5분
    const cacheNoticeSec = 3600; //공지사항(1시간)
    const cacheOnairSec = 10; //10초
    
    const cacheMyStationSec = 60; //1분 내 방송국
    
    const cachePageSec = 3600; //86400; //1일
    const cacheItemListSec = 3600; //1일 아이템
}

?>
