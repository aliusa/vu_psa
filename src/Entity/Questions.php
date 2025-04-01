<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('questions')]
#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Questions extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    #[AssertValidator\NotBlank]
    #[AssertValidator\Length(max: 1000)]
    #[ORM\Column(type: Types::TEXT, length: 1000, nullable: false)]
    public string $question;

    #[AssertValidator\Email]
    #[AssertValidator\Length(max: 250)]
    #[ORM\Column(type: Types::STRING, length: 250, nullable: true)]
    public ?string $email = null;

    /**
     * This is the owning side.
     * @see Users::$questions
     * @var \Proxies\__CG__\App\Entity\Users|Users
     */
    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'users_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public ?Users $users = null;

    /**
     * One questions have Many answers.
     * @see QuestionsAnswers::$questions
     * @var PersistentCollection|QuestionsAnswers[]
     */
    #[ORM\OneToMany(targetEntity: QuestionsAnswers::class, mappedBy: 'questions', cascade: ['persist', 'remove'])]
    public $questions_answers;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return '[#' . $this->id . '] ' . $this->question;
    }

    public function getQuestionsAnswers(): array
    {
        return $this->questions_answers->toArray();
    }
}
