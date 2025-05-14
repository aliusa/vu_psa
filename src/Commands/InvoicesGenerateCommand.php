<?php

namespace App\Commands;

use App\Entity\Invoices;
use App\Entity\UsersObjectsServicesBundles;
use App\Repository\InvoicesRepository;
use App\Repository\UsersObjectsServicesBundlesRepository;
use DateInterval;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Lock\LockFactory;

class InvoicesGenerateCommand extends BaseCommand
{
    public static $defaultName = 'invoices:generate';
    protected static $defaultDescription = 'Sugeneruoja sąskaitas';
    private \Doctrine\ORM\QueryBuilder $invoicesQuery;
    private \DateTime $today;

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
        protected InvoicesRepository $invoicesRepository,
    )
    {
        parent::__construct($container, $lockFactory);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $offset = 0;
        $limit = 10;


        $this->invoicesQuery = $this->invoicesRepository
            ->createQueryBuilder('invoices')
            ->andWhere('invoices.period_start = :period_start')/** @see Invoices::$period_start */
            ->andWhere('invoices.users_objects_services_bundles = :users_objects_services_bundles')/** @see Invoices::$users_objects_services_bundles */
        ;
        $this->today = new \DateTime();

        /** @var UsersObjectsServicesBundles[] $usersObjectsServicesBundles */
        while (
            $usersObjectsServicesBundles = $this->usersObjectsServicesBundlesRepository
                ->createQueryBuilder('item')
                //->andWhere('item.created_at >= :firstDay')->setParameter('firstDay', $firstDay)
                //->andWhere('item.active_to >= :firstDay')->setParameter('firstDay', $firstDay)
                ->orderBy('item.id', 'ASC')
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getQuery()->getResult()
        ) {
            foreach ($usersObjectsServicesBundles as $usersObjectsServicesBundle) {
                $this->generateInvoice($usersObjectsServicesBundle);
            }
            $offset += $limit;

            //clearup memory
            $this->manager->getManager()->clear();
        }

        $this->io->success('DONE');
        return static::SUCCESS;
    }

    private function generateInvoice(UsersObjectsServicesBundles $usersObjectsServicesBundles)
    {
        //Ar active_to yra 1 mėnesio diena?

        $period = new \DatePeriod(
            new \DateTime($usersObjectsServicesBundles->active_from->format('Y-m-1')),
            new \DateInterval('P1M'),
            (new \DateTime($this->today->format('Y-m-1')))->sub(new \DateInterval('P1D')),
        );
        foreach ($period as $key => $value) {
            $invoices = $this->invoicesQuery
                ->setParameter('period_start', $value->format('Y-m-d'))/** @see Invoices::$period_start */
                ->setParameter('users_objects_services_bundles', $usersObjectsServicesBundles)/** @see Invoices::$users_objects_services_bundles */
                ->getQuery()
                ->getResult()
                ;
            if ($invoices) {
                continue;
            }

            $periodStart = clone $value;
            $dueDate = $value->add(new \DateInterval('P1M'))->add(new DateInterval('P15D'));

            //create invoice
            $invoice = new Invoices();
            $invoice->users_objects_services_bundles = $usersObjectsServicesBundles;
            $invoice->period_start = clone $periodStart;
            $invoice->period_end = $periodStart->modify('last day of this month');
            $invoice->due_date = $dueDate;
            $invoice->is_paid = null;
            $invoice->series = 'SAS';
            if ($no = $this->invoicesRepository->createQueryBuilder('invoices')->select('invoices.no')->orderBy('invoices.no', 'DESC')->getQuery()->setMaxResults(1)->getResult()) {
                $invoice->no = reset($no[0]) + 1;
            } else {
                $invoice->no = 1;
            }
            $this->manager->getManager()->persist($invoice);
            $this->manager->getManager()->flush();
        }
    }
}
