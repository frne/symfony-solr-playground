<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository\Solr;

use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Frne\Bundle\SolrSearchBundle\Entity\Repository\SortableListRepositoryInterface;
use Solarium\Client;
use SPF\SolrQueryBuilder\QueryBuilder;
use SPF\SolrQueryBuilder\Query\QueryInterface;

class ArticleRepository implements FulltextSearchRepositoryInterface, SortableListRepositoryInterface
{
    /**
     * @var Client
     */
    private $solr;

    /**
     * @param Client $solr
     */
    public function __construct(Client $solr)
    {
        $this->solr = $solr;
    }

    /**
     * {@inheritdoc}
     */
    public function findByFulltext($search, $limit = 10, $offset = 0)
    {
        $qb = new QueryBuilder(QueryBuilder::VERSION_4);
        $query = $qb->select()
            ->nest()
            ->where('id', $search)
            ->orWhere('title_s', $search, QueryInterface::WILDCARD_SURROUNDED)
            ->orWhere('author_s', $search, QueryInterface::WILDCARD_SURROUNDED)
            ->orWhere('content_t', $search, QueryInterface::WILDCARD_SURROUNDED)
            ->endNest()
            ->andWhere('document_name_s', 'article')
            ->getQueryString();

        $select = $this->solr->createSelect();
        $select->setQuery($query);
        $select->setRows((int)$limit);
        $select->setStart((int)$offset);
        $select->addSort('title_s', 'ASC');

        return $this->solr->select($select);
    }

    /**
     * {@inheritdoc}
     */
    public function findAllSortBy($sortBy = self::SORT_BY_ID, $limit = 10, $offset = 0)
    {
        $qb = new QueryBuilder(QueryBuilder::VERSION_4);
        $query = $qb->select()
            ->where('document_name_s', 'article')
            ->getQueryString();

        $select = $this->solr->createSelect();
        $select->setQuery($query);
        $select->setRows((int)$limit);
        $select->setStart((int)$offset);

        switch ($sortBy) {
            case self::SORT_BY_ID:
                $select->addSort('id', 'ASC');
                break;
            case self::SORT_BY_TITLE:
                $select->addSort('title_s', 'ASC');
                break;
            case self::SORT_BY_CONTENT:
                $select->addSort('content_t', 'ASC');
                break;
            case self::SORT_BY_AUTHOR:
                $select->addSort('author_s', 'ASC');
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid sort type %s!', $sortBy));
        }

        return $this->solr->select($select);
    }
} 