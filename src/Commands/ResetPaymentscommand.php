<?php

namespace App\Commands;

use App\Entity\Invoices;
use App\Entity\Payments;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Lock\LockFactory;

class ResetPaymentscommand extends BaseCommand
{
    public static $defaultName = 'payments:reset';
    protected static $defaultDescription = 'Ištrina Payments';

    protected function configure(): void
    {
        $this
            ->setName(static::$defaultName)
            ->setDescription(static::$defaultDescription)
        ;
    }

    public function __construct(
        ContainerInterface $container,
        LockFactory $lockFactory,
        protected ManagerRegistry $manager,
    )
    {
        parent::__construct($container, $lockFactory);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Connection $connection */
        $connection = $this->manager->getConnection();
        $tableName = $this->manager->getManager()->getClassMetadata(Payments::class)->getTableName();
        $connection->delete($tableName);
        $tableName = $this->manager->getManager()->getClassMetadata(Invoices::class)->getTableName();
        $connection->update($tableName, [
            'is_paid' => null,
        ]);

        $this->io->success('DONE');
        return static::SUCCESS;
    }
}
