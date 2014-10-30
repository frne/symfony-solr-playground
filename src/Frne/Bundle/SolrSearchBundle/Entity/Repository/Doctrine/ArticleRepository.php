<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;
use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Frne\Bundle\SolrSearchBundle\Entity\Repository\SortableListRepositoryInterface;

class ArticleRepository extends EntityRepository implements FulltextSearchRepositoryInterface, SortableListRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByFulltext($search, $limit = 10, $offset = 0)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a')
            ->from('FrneSolrSearchBundle:Article', 'a')
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

    /**
     * {@inheritdoc}
     */
    public function findAllSortBy($sortBy = self::SORT_BY_ID, $limit = 10, $offset = 0)
    {
        switch ($sortBy) {
            case self::SORT_BY_ID:
            case self::SORT_BY_TITLE:
            case self::SORT_BY_CONTENT:
            case self::SORT_BY_AUTHOR:
            return $this->getEntityManager()
                ->createQuery(
                    sprintf('SELECT DISTINCT a FROM FrneSolrSearchBundle:Article a ORDER BY a.%s ASC', $sortBy)
                )->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getResult();
            default:
                throw new \InvalidArgumentException(sprintf('Invalid sort type %s!', $sortBy));
        }


    }


}