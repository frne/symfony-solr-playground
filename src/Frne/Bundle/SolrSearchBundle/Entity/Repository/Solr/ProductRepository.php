<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository\Solr;

use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Frne\Bundle\SolrSearchBundle\Solr\RawQuery;
use FS\SolrBundle\Repository\Repository;
use FS\SolrBundle\Doctrine\Hydration\HydrationModes;
use FS\SolrBundle\Solr;
use Frne\Bundle\SolrSearchBundle\Entity\Product;
use SPF\SolrQueryBuilder\Query\QueryInterface;
use SPF\SolrQueryBuilder\QueryBuilder;

class ProductRepository extends Repository implements FulltextSearchRepositoryInterface
{
    /**
     * @var \FS\SolrBundle\Solr
     */
    private $solr;

    /**
     * @var object
     */
    private $entity;

    /**
     * Constructor workaround because however Repository:$solr and Repository:$entity are private
     * @see https://github.com/floriansemm/SolrBundle/pull/87
     *
     * @param Solr $solr
     * @param object $entity
     */
    public function __construct(Solr $solr, $entity)
    {
        $this->solr = $solr;
        $this->entity = $entity;

        parent::__construct($solr, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function findByFulltext($search, $limit = 10, $offset = 0)
    {
        $this->hydrationMode = HydrationModes::HYDRATE_DOCTRINE;

        $qb = new QueryBuilder(QueryBuilder::VERSION_4);
        $query = $qb->select()
            ->nest()
                ->where('id', $search)
                ->orWhere('title_s', $search, QueryInterface::WILDCARD_SURROUNDED)
                ->orWhere('content_t', $search, QueryInterface::WILDCARD_SURROUNDED)
            ->endNest()
            ->andWhere('document_name_s', 'product')
            ->getQueryString();

        $query = new RawQuery($query);

        $query->setEntity(new Product());
        $query->setRows($limit);
        $query->setStart($offset);
        $query->setSolr($this->solr);
        $query->setHydrationMode($this->hydrationMode);

        return $this->solr->query($query);
    }
}