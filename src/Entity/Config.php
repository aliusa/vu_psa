<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'configs')]
#[ORM\Entity(repositoryClass: ConfigRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Config extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    #[ORM\Column(type: 'string', name: '`key`')]
    public string $key;

    #[ORM\Column(type: 'json', nullable: true)]
    public $value = null;
}
