<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;
use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;

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
            ->orderBy('a.title', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}