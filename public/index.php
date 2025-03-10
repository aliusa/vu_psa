<?php

use App\Kernel;

umask(0000);// This will let the permissions be 0777

if(($_SERVER['SERVER_NAME'] ?? null) === 'vu-psa.lt'){
    //force https usage
    $_SERVER['HTTPS'] = 'on';
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

require_once '../bin/debug.php';

return function (array $context) {

    define('USE_IP_PROXY', true);


    if (!empty($context['APP_DEBUG_ALLOW_LIST'])) {
        if (
            (!empty($context['APP_USE_IP_PROXY']))
            AND ($X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null)
        ) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            if(defined('TRUSTED_IP_PROXIES')){
                $ips = array_diff($ips, TRUSTED_IP_PROXIES);
            }
            $ip = trim(array_pop($ips));
        } elseif ($REMOTE_ADDR = $_SERVER['REMOTE_ADDR'] ?? null) {
            $ip = trim($REMOTE_ADDR);
        } else {
            $ip = null;
        }

        //NULL - terminal
        $allowList = str_replace(['*', '.'], ['\d+', '\.'], $context['APP_DEBUG_ALLOW_LIST']);

        $debug = $ip === null || preg_match("/^{$allowList}$/", $ip);

        return new Kernel($debug ? $context['APP_ENV'] : 'prod', $debug?: ((bool) $context['APP_DEBUG']));

    }else{
        return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    }


};
