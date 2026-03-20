<?php

namespace App\Repository;

use App\Entity\Conference;
use App\Entity\Skill;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function findBetweenDates(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        if (null === $start && null === $end) {
            throw new \InvalidArgumentException('At least one of the dates must be provided.');
        }

        $qb = $this->createQueryBuilder('c');

        if ($start instanceof \DateTimeImmutable) {
            $qb->andWhere($qb->expr()->gte('c.startAt', ':start'))
                ->setParameter('start', $start);
        }

        if ($end instanceof \DateTimeImmutable) {
            $qb->andWhere($qb->expr()->lte('c.endAt', ':end'))
                ->setParameter('end', $end);
        }

        return $qb->getQuery()->getResult();
    }

    public function findLikeName(string $name): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where($qb->expr()->like('c.name', ':name'))
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }

    public function findForTags(User $user): iterable
    {
        $qb = $this->createQueryBuilder('c');
        $tagIds = $user
            ->getProfile()
            ->getInterests()
            ->map(fn(Tag $tag) => $tag->getId());

        return $qb
            ->innerJoin('c.tags', 't')
            ->where($qb->expr()->in('t.id', ':tagIds'))
            ->setParameter('tagIds', $tagIds)
            ->groupBy('c.id')
            ->orderBy($qb->expr()->count('t.id'), 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findForSkills(User $user): iterable
    {
        $qb = $this->createQueryBuilder('c');
        $skillIds = $user
            ->getProfile()
            ->getSkills()
            ->map(fn(Skill $skill) => $skill->getId());

        return $qb
            ->innerJoin('c.neededSkills', 's')
            ->where($qb->expr()->in('s.id', ':skillIds'))
            ->setParameter('skillIds', $skillIds)
            ->groupBy('c.id')
            ->orderBy($qb->expr()->count('s.id'), 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Conference[] Returns an array of Conference objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conference
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
