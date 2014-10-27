<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;
use Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\FulltextSearchRepositoryInterface;

class ArticleRepository extends EntityRepository implements FulltextSearchRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByFulltext($search, $limit = 10, $offset = 0)
    {
        return $this->createQueryBuilder('a')
            ->where('a.id = :query')
            ->orWhere('a.title LIKE :query')
            ->orWhere('a.author LIKE :query')
            ->orWhere('a.content LIKE :query')
            ->setParameter('query', '%' . $search . '%')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}