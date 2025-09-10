<?php

namespace App\Entity;

use App\Repository\QuestionsCategoriesRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('questions_categories')]
#[ORM\Entity(repositoryClass: QuestionsCategoriesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class QuestionsCategories extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * One category have Many questions.
     * @see Questions::$questions_categories
     * @var PersistentCollection|Questions[]
     */
    #[ORM\OneToMany(targetEntity: Questions::class, mappedBy: 'questions_categories', cascade: [])]
    public $questions;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $title;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->title]);
    }
}
