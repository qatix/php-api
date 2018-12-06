<?php

define('APP_DIR', dirname(__FILE__) . '/..');
define('DIR_APPLICATION', APP_DIR . '/app/');
define('DIR_SYSTEM', APP_DIR . '/system/');
define('DIR_DATABASE', APP_DIR . '/system/database/');
define('DIR_CONFIG', APP_DIR . '/system/config/');

//CACHE TIME CONSTANT
define('CACHE_ONE_DAY', 86400);
define('CACHE_ONE_HOUR', 3600);
define('CACHE_HALF_DAY', 43200);
define('CACHE_TEN_HOUR', 36000);
define('CACHE_HALF_AN_HOUR', 1800);
define('CACHE_FIVE_MINUTES', 300);
define('CACHE_3_DAYS', 252900);
define('CACHE_7_DAYS', 604800);
define('CACHE_30_DAYS', 2529000);
//Qiniu
define('IMAGE_UPLOAD_URL', 'http://up.qiniu.com/');
//test local
define('PUBLIC_BUCKET', 'ccwk01');
define('PRIVATE_BUCKET', 'ccwk02');
define('QINIU_HOSTNAME', 'qiniudn.com');

define('SYSTEM_MONEY_LIMIT', 100000);
define('INFINITY', 100000000);
define('DEFAULT_PAGE_LIMIT', 20);
