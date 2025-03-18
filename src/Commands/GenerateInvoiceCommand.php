<?php

namespace App\Commands;

use App\Entity\UsersObjectsServicesBundles;
use App\Repository\UsersObjectsServicesBundlesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Lock\LockFactory;

class GenerateInvoiceCommand extends BaseCommand
{
    protected static $defaultName = 'invoice:generate';
    protected static $defaultDescription = 'Sugeneruoja sąskaitas';

    protected function configure(): void
    {
        $this
            ->setName(static::$defaultName)
            ->setDescription(static::$defaultDescription)
            ->setLock(3600)
        ;
    }

    public function __construct(
        ContainerInterface $container,
        LockFactory $lockFactory,
        protected ManagerRegistry $manager,
        protected UsersObjectsServicesBundlesRepository $usersObjectsServicesBundlesRepository,
    )
    {
        parent::__construct($container, $lockFactory);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstDay = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1));//Jei dabar 2025-03-11, tai gausim 2025-02-01

        $offset = 0;
        $limit = 10;

        /** @var UsersObjectsServicesBundles[] $items */
        while (
            $items = $this->usersObjectsServicesBundlesRepository
                ->createQueryBuilder('item')
                //->andWhere('item.created_at >= :firstDay')->setParameter('firstDay', $firstDay)
                ->andWhere('item.active_to >= :firstDay')->setParameter('firstDay', $firstDay)
                ->orderBy('item.id', 'ASC')
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getQuery()->getResult()
        ) {
            foreach ($items as $item) {
                $this->generateInvoice($item);
            }
            $offset += $limit;

            //clearup memory
            $this->manager->getManager()->clear();
        }

        $this->io->success("DONE");
        return static::SUCCESS;
    }

    private function generateInvoice(UsersObjectsServicesBundles $usersObjectsServicesBundles)
    {
        dv($usersObjectsServicesBundles);
    }
}
