<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository;

interface FulltextSearchRepositoryInterface
{
    /**
     * Returns a list of entities using a fulltext-search
     *
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findByFulltext($search, $limit = 10, $offset = 0);
} 