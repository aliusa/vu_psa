<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

abstract class BaseCommand extends Command
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    protected SymfonyStyle $io;
    protected ContainerInterface $container;
    protected LockFactory $lockFactory;

    protected int $lockTtl = 0;
    protected ?LockInterface $lock = null;

    public function __construct(ContainerInterface $container, ?LockFactory $lockFactory = null)
    {
        parent::__construct();
        $this->container = $container;
        if($lockFactory){
            $this->lockFactory = $lockFactory;
        }

        ini_set('memory_limit', '2048M');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        if($this->lockTtl){
            $this->lock = $this->lockFactory->createLock(get_called_class(), $this->lockTtl);
            if(! $this->lock->acquire()){
                $this->io->warning('locked');
                return static::FAILURE;
            }
            $this->io->writeln("Lock acquired");
        }

        $exitCode = parent::run($input, $output);

        if($this->lockTtl){
            $this->lock->release();
            $this->io->writeln("Lock released");
        }

        return $exitCode;
    }

    /**
     * @param int $ttl seconds >=0
     */
    public function setLock(int $ttl)
    {
        if ($ttl < 0) {
            throw new \InvalidArgumentException('Must be above or equal 0.');
        }

        $this->lockTtl = $ttl;
        return $this;
    }
}
