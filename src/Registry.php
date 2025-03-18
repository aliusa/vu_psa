<?php

namespace App;

use App\Entity\Invoices;
use App\Repository\InvoicesRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class Registry
{
    protected static ?ContainerInterface $container = null;
    protected static ?ManagerRegistry $doctrine = null;
    private static ?Registry $instance = null;

    public function __construct()
    {
        /** @var Kernel|Application $kernelApp */
        $kernelApp = $GLOBALS['app'];

        if ($kernelApp instanceof Kernel) {
            static::$container = $kernelApp->getContainer();
            static::$doctrine = $kernelApp->getContainer()->get('doctrine');
        } elseif ($kernelApp instanceof Application) {
            static::$container = $kernelApp->getKernel()->getContainer();
            static::$doctrine = $kernelApp->getKernel()->getContainer()->get('doctrine');
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
}
