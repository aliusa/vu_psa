<?php

namespace App\Entity;

use App\Entity\Payments\BasePayment;
use App\Repository\PaymentsRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('payments')]
#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Payments extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see Invoices::$payments
     * @var Invoices
     */
    #[ORM\ManyToOne(targetEntity: Invoices::class, cascade: [], inversedBy: 'payments')]
    #[ORM\JoinColumn(name: 'invoices_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public Invoices $invoices;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    public $hash;

    //#[AssertValidator\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: false)]
    public string $payment_method;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $redirect_url;
    #[AssertValidator\Range(min: 0, max: 100000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $total;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public $raw_request_data;
    #[ORM\Column(type: Types::JSON, nullable: true)]
    public $request_data;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $status;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public $response_data;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public string $return_data;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    public ?\DateTime $payment_date = null;

    public function __construct()
    {
        parent::__construct(...func_get_args());
        //$this->users_objects = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->hash = BasePayment::generateHash();
    }

    public function __toString()
    {
        return '[#' . $this->id . '] ';
    }
}
