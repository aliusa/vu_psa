<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS AssertValidator;

#[ORM\Table('users_objects_services')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServices extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServicesBundles::$users_objects_services
     * @var \Proxies\__CG__\App\Entity\UsersObjectsServicesBundles|UsersObjectsServicesBundles
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_objects_services_bundles;

    /**
     * This is the owning side.
     * @see Services::$users_objects_services
     * @var \Proxies\__CG__\App\Entity\Services|Services
     */
    #[ORM\ManyToOne(targetEntity: Services::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'services_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $services;

    #[AssertValidator\Range(min: 1, max: 100)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $amount = 1;

    /**
     * @var int Vnt. kaina su PVM.
     */
    #[AssertValidator\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $unit_price = 0;

    /**
     * @var float PVM %.
     */
    #[AssertValidator\Range(min: 0, max: 100)]
    #[ORM\Column(type: Types::FLOAT, nullable: false, precision: 2, options: ['default' => 21])]
    public float $unit_vat;

    /**
     * @var float Vnt. kaina be PVM.
     */
    #[AssertValidator\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::FLOAT, nullable: false, precision: 2)]
    public float $unit_price_vat = 0;

    /**
     * @var float Viso suma su PVM. "unit_price * amount"
     */
    #[AssertValidator\Range(min: 0, max: 100000)]
    #[ORM\Column(type: Types::FLOAT, nullable: false, precision: 2)]
    public float $total_price;

    /**
     * @var float Viso suma be PVM. "total_price / 100 * unit_vat"
     */
    #[AssertValidator\Range(min: 0, max: 100000)]
    #[ORM\Column(type: Types::FLOAT, nullable: false, precision: 2)]
    public float $total_price_vat;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: ['default' => 'CURRENT_DATE'])]
    public \DateTime $active_from;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: [])]
    public \DateTime $active_to;
    private int $daysInMonthTotal = 0;
    private int $daysInMonth = 0;
    private float $daysInMonthPercentage = 100;

    public function __construct()
    {
        parent::__construct();

        $this->active_from = new \DateTime();
        if (!$this->unit_price_vat) {
            $this->unit_price_vat = $this->unit_price - $this->unit_price * 0.21;
        }
    }

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", ]);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function calculateTotalPrice(): void
    {
        $this->unit_price_vat = $this->unit_price - $this->unit_price * $this->unit_vat;
        $this->total_price_vat = $this->unit_price_vat * $this->amount;
        $this->total_price = $this->unit_price * $this->amount;
    }

    public function isUsersObjectsServicesActive(): bool
    {
        return $this->active_to > new \DateTime();
    }

    public function isFullPeriod(Invoices $invoices): bool
    {
        return $this->active_from <= $invoices->period_start
            && $this->active_to >= $invoices->period_end;
            ;
    }

    public function setVat(float $vat): void
    {
        $this->unit_vat = $vat;
    }

    /**
     * @param Invoices $invoices
     * @return float 0-100
     */
    protected function getDaysInMonthPercentage(Invoices $invoices): float
    {
        if (!$this->daysInMonthTotal) {
            $this->daysInMonth = $this->daysInMonthTotal = $invoices->period_start->diff($invoices->period_end)->days + 1;

            //Jeigu prasidėjo pvz 10dieną
            if ($this->active_from > $invoices->period_start) {
                //tada 9dienų nėra
                $this->daysInMonth -= $invoices->period_start->diff($this->active_from)->days;
            }
            if ($this->active_to < $invoices->period_end) {
                $this->daysInMonth -= $invoices->period_end->diff($this->active_to)->days;
            }
            return $this->daysInMonthPercentage = round($this->daysInMonth / $this->daysInMonthTotal * 100, 2);
        }

        return $this->daysInMonthPercentage;
    }

    protected function getAdjustedMoney(Invoices $invoices, float $money): float
    {
        return round($this->getDaysInMonthPercentage($invoices) / 100 * $money, 2);
    }
    public function getAdjustedUnitPriceVat(Invoices $invoices)
    {
        if ($this->isFullPeriod($invoices)) {
            return $this->unit_price_vat;
        }
        return $this->getAdjustedMoney($invoices, $this->unit_price_vat);
    }

    public function getAdjustedUnitPrice(Invoices $invoices)
    {
        if ($this->isFullPeriod($invoices)) {
            return $this->unit_price;
        }
        return $this->getAdjustedMoney($invoices, $this->unit_price);
    }

    public function getAdjustedTotalPriceVat(Invoices $invoices)
    {
        if ($this->isFullPeriod($invoices)) {
            return $this->total_price_vat;
        }
        return $this->getAdjustedMoney($invoices, $this->total_price_vat);
    }

    public function getAdjustedTotalPrice(Invoices $invoices)
    {
        if ($this->isFullPeriod($invoices)) {
            return $this->total_price;
        }
        return $this->getAdjustedMoney($invoices, $this->total_price);
    }
}
