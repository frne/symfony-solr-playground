<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\Solr;

use Frne\Bundle\SymfonyPlaygroundBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Solarium\Client;

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
        $select = $this->solr->createSelect();
        $select->setQuery(
            sprintf(
                '(id:%s OR title_s:*%s* OR author_s:*%s*) AND document_name_s:article',
                $search,
                $search,
                $search
            )
        );
        $select->setRows((int)$limit);
        $select->setStart((int)$offset);

        return $this->solr->select($select);
    }
} 