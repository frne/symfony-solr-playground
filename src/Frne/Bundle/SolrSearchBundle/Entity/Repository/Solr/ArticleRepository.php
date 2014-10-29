<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository\Solr;

use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Solarium\Client;
use SPF\SolrQueryBuilder\QueryBuilder;
use SPF\SolrQueryBuilder\Query\QueryInterface;

class ArticleRepository implements FulltextSearchRepositoryInterface
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
} 