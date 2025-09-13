<?php

namespace App\Tests\WebTestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

abstract class AbtractWithMigrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel(['environment' => 'test']);
        $application = new Application($kernel);
        $command = $application->find('doctrine:migrations:migrate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['n']);
    }
}
