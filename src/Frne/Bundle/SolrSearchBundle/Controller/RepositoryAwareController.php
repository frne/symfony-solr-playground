<?php

namespace Frne\Bundle\SolrSearchBundle\Controller;

use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Symfony\Component\Stopwatch\Stopwatch;

abstract class RepositoryAwareController
{
    /**
     * @var FulltextSearchRepositoryInterface
     */
    protected $doctrineArticleRepository;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    protected $doctrineProductRepository;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    protected $solrArticleRepository;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    protected $solrProductRepository;

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @param FulltextSearchRepositoryInterface $doctrineArticleRepository
     * @param FulltextSearchRepositoryInterface $doctrineProductRepository
     * @param FulltextSearchRepositoryInterface $solrArticleRepository
     * @param FulltextSearchRepositoryInterface $solrProductRepository
     * @param Stopwatch $stopwatch
     */
    public function __construct(
        FulltextSearchRepositoryInterface $doctrineArticleRepository = null,
        FulltextSearchRepositoryInterface $doctrineProductRepository = null,
        FulltextSearchRepositoryInterface $solrArticleRepository = null,
        FulltextSearchRepositoryInterface $solrProductRepository = null,
        Stopwatch $stopwatch = null
    ) {
        $this->doctrineArticleRepository = $doctrineArticleRepository;
        $this->doctrineProductRepository = $doctrineProductRepository;
        $this->solrArticleRepository = $solrArticleRepository;
        $this->solrProductRepository = $solrProductRepository;
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param FulltextSearchRepositoryInterface $doctrineArticleRepository
     * @return RepositoryAwareController
     */
    public function setDoctrineArticleRepository($doctrineArticleRepository)
    {
        $this->doctrineArticleRepository = $doctrineArticleRepository;

        return $this;
    }

    /**
     * @param FulltextSearchRepositoryInterface $doctrineProductRepository
     * @return RepositoryAwareController
     */
    public function setDoctrineProductRepository($doctrineProductRepository)
    {
        $this->doctrineProductRepository = $doctrineProductRepository;

        return $this;
    }

    /**
     * @param FulltextSearchRepositoryInterface $solrArticleRepository
     * @return RepositoryAwareController
     */
    public function setSolrArticleRepository($solrArticleRepository)
    {
        $this->solrArticleRepository = $solrArticleRepository;

        return $this;
    }

    /**
     * @param FulltextSearchRepositoryInterface $solrProductRepository
     * @return RepositoryAwareController
     */
    public function setSolrProductRepository($solrProductRepository)
    {
        $this->solrProductRepository = $solrProductRepository;

        return $this;
    }

    /**
     * @param Stopwatch $stopwatch
     * @return RepositoryAwareController
     */
    public function setStopwatch($stopwatch)
    {
        $this->stopwatch = $stopwatch;

        return $this;
    }
} 