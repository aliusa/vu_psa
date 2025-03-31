<?php

namespace App\Entity;

use App\Repository\QuestionsAnswersRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('questions_answers')]
#[ORM\Entity(repositoryClass: QuestionsAnswersRepository::class)]
#[ORM\HasLifecycleCallbacks]
class QuestionsAnswers extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * One User have Many Questions.
     * @see Questions::$questions_answers
     * @var \Proxies\__CG__\App\Entity\Questions|Questions
     */
    #[ORM\ManyToOne(targetEntity: Questions::class, inversedBy: 'questions_answers', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'questions_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public Questions $questions;

    #[AssertValidator\NotBlank]
    #[AssertValidator\Length(max: 1000)]
    #[ORM\Column(type: Types::TEXT, length: 1000, nullable: false)]
    public string $answer;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return '[#' . $this->id . '] ' . $this->answer;
    }
}
