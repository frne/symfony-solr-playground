<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\Solr;

use Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Frne\Bundle\SymfonyPlaygroundBundle\Solr\RawQuery;
use FS\SolrBundle\Repository\Repository;
use FS\SolrBundle\Doctrine\Hydration\HydrationModes;
use FS\SolrBundle\Solr;
use Frne\Bundle\SymfonyPlaygroundBundle\Entity\Product;

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

        $query = new RawQuery();

        $query->setQuery(
            sprintf(
                '(id:*%s* OR title_s:*%s* OR content_t:*%s*) AND document_name_s:product',
                $search,
                $search,
                $search,
                $search
            )
        );
        $query->setEntity(new Product());
        $query->setRows($limit);
        $query->setStart($offset);
        $query->setSolr($this->solr);
        $query->setHydrationMode($this->hydrationMode);

        return $this->solr->query($query);
    }
}