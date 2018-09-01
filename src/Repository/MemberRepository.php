<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Member::class);
    }

    /**
     * @param array $filters
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getDataProvider(array $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->addOrderBy('m.name', 'ASC')
            ->addOrderBy('m.created_at', 'DESC');

        if (!empty($filters['name'])) {
            $queryBuilder->where('m.name LIKE :name')
                ->setParameter(':name', "%{$filters['name']}%");
        }

        return $queryBuilder;
    }
}
