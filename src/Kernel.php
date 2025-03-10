<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;
    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function __construct(string $environment, bool $debug)
    {
        ini_set('date.timezone', 'Europe/Vilnius');
        parent::__construct($environment, $debug);

        if(!empty($_ENV['TRUSTED_PROXIES'])){

            $headers =
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO |
                Request::HEADER_X_FORWARDED_AWS_ELB;

            Request::setTrustedProxies(explode(',', $_ENV['TRUSTED_PROXIES']), $headers);
        }

    }
}
