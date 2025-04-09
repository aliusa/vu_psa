<?php

namespace App\Repository;

use App\Entity\QuestionsAnswers;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionsAnswers|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionsAnswers|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionsAnswers[]    findAll()
 * @method QuestionsAnswers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsAnswersRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionsAnswers::class);
    }
}
