<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getProjectsUser(int $id): array
    {
      $qb = $this->createQueryBuilder('p')
          ->where('p.user = :user_id')
          ->orderBy('p.deadline', 'DESC')
          ->setParameter('user_id', $id);

      $query = $qb->getQuery();
      return $query->execute();
    }

    public function getProjectTasksUser(int $project_id): array
    {
      $qb = $this->createQueryBuilder('p')
          ->leftJoin("p.tasks", "t")
          ->addSelect("t")
          ->where('p.id = :project_id')
          ->setParameter('project_id', $project_id)
          ->orderBy("t.deadline", 'DESC');

      $query = $qb->getQuery();
      return $query->execute();
    }

}
