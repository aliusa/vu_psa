<?php
/**
 * @copyright JSC Dizaino kryptis 2025
 *
 * This Software is the property of Dizaino Kryptis
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact JSC Dizaino kryptis:
 * E-mail: info@kryptis.lt
 * https://www.kryptis.lt
 *
 * @author Alius Sultanovas
 * @date 2025-09-10
 */

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
