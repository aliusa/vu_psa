<?php

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Registry
{
    protected static ?ContainerInterface $container = null;
    protected static ?ManagerRegistry $doctrine = null;
    private static ?Registry $instance = null;
    private static Kernel|Application $kernelApp;

    public function __construct()
    {
        static::$kernelApp = $GLOBALS['app'];

        if (static::$kernelApp instanceof Kernel) {
            static::$container = static::$kernelApp->getContainer();
            static::$doctrine = static::$kernelApp->getContainer()->get('doctrine');
        } elseif (static::$kernelApp instanceof Application) {
            static::$container = static::$kernelApp->getKernel()->getContainer();
            static::$doctrine = static::$kernelApp->getKernel()->getContainer()->get('doctrine');
        }

        return self::class;
    }

    public static function getDoctrine(): ?ManagerRegistry
    {
        is_null(self::$instance) && static::$instance = new self();
        return static::$doctrine;
    }
    public static function getContainer():?ContainerInterface{
        return static::$container;
    }
    public static function getDoctrineConnection(?string $name = null): Connection
    {
        return static::getDoctrine()->getConnection($name);
    }
    public static function getDoctrineManager(): \Doctrine\Persistence\ObjectManager
    {
        return static::getDoctrine()->getManager();
    }

    public static function getRouter(): Router
    {
        is_null(self::$instance) && static::$instance = new self();
        return static::$container->get('router');
    }
    public static function getKernel(): Kernel
    {
        is_null(self::$instance) && static::$instance = new self();
        return static::$kernelApp;
    }
}
