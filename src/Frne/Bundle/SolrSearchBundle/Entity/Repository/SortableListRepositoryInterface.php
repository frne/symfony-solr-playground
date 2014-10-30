<?php

namespace Frne\Bundle\SolrSearchBundle\Entity\Repository;

interface SortableListRepositoryInterface
{
    const SORT_BY_ID = 'id';

    const SORT_BY_TITLE = 'title';

    const SORT_BY_CONTENT = 'content';

    const SORT_BY_AUTHOR = 'author';

    /**
     * @param string $sortBy
     * @param int $limit
     * @param int $offset
     * @throws \InvalidArgumentException
     * @return array
     */
    public function findAllSortBy($sortBy = self::SORT_BY_ID, $limit = 10, $offset = 0);
} 