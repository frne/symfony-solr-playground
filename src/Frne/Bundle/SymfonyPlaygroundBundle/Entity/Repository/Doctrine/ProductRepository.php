<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;
use Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\FulltextSearchRepositoryInterface;

class ProductRepository extends EntityRepository implements FulltextSearchRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByFulltext($search, $limit = 10, $offset = 0)
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :query')
            ->orWhere('p.title LIKE :query')
            ->orWhere('p.content LIKE :query')
            ->setParameter('query', '%' . $search . '%')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}