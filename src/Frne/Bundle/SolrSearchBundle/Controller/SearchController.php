<?php

namespace Frne\Bundle\SolrSearchBundle\Controller;

use Frne\Bundle\SolrSearchBundle\Entity\Repository\FulltextSearchRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SearchController
{
    const PARAM_QUERY = 'q';

    const PARAM_LIMIT = 'limit';

    const PARAM_OFFSET = 'offset';

    const PARAM_LIMIT_DEFAULT = 10;

    const PARAM_OFFSET_DEFAULT = 0;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    private $doctrineArticleRepository;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    private $doctrineProductRepository;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    private $solrArticleRepository;

    /**
     * @var FulltextSearchRepositoryInterface
     */
    private $solrProductRepository;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @param FulltextSearchRepositoryInterface $doctrineArticleRepository
     * @param FulltextSearchRepositoryInterface $doctrineProductRepository
     * @param FulltextSearchRepositoryInterface $solrArticleRepository
     * @param FulltextSearchRepositoryInterface $solrProductRepository
     * @param Stopwatch $stopwatch
     */
    public function __construct(
        FulltextSearchRepositoryInterface $doctrineArticleRepository,
        FulltextSearchRepositoryInterface $doctrineProductRepository,
        FulltextSearchRepositoryInterface $solrArticleRepository,
        FulltextSearchRepositoryInterface $solrProductRepository,
        Stopwatch $stopwatch
    ) {
        $this->doctrineArticleRepository = $doctrineArticleRepository;
        $this->doctrineProductRepository = $doctrineProductRepository;
        $this->solrArticleRepository = $solrArticleRepository;
        $this->solrProductRepository = $solrProductRepository;
        $this->stopwatch = $stopwatch;
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function searchFulltextAction(Request $request)
    {
        if (is_null($query = $request->get(self::PARAM_QUERY))) {
            return array(
                'query' => $query,
                'result' => false
            );
        } else {
            $this->stopwatch->start('doctrine');
            $doctrineResult = $this->doctrineArticleRepository
                ->findByFulltext(
                    $query,
                    (int)$request->get(self::PARAM_LIMIT, self::PARAM_LIMIT_DEFAULT),
                    (int)$request->get(self::PARAM_OFFSET, self::PARAM_OFFSET_DEFAULT)
                );
            $doctrineTime = $this->stopwatch->stop('doctrine');

            $this->stopwatch->start('solr');
            $solrResult = $this->solrArticleRepository
                ->findByFulltext(
                    $query,
                    (int)$request->get(self::PARAM_LIMIT, self::PARAM_LIMIT_DEFAULT),
                    (int)$request->get(self::PARAM_OFFSET, self::PARAM_OFFSET_DEFAULT)
                );
            $solrTime = $this->stopwatch->stop('solr');

            return array(
                'query' => $query,
                'timing' => array(
                    'solr' => $solrTime,
                    'mysql' => $doctrineTime
                ),
                'result' => array(
                    'solr' => $solrResult,
                    'mysql' => $doctrineResult
                )
            );
        }
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function searchHydrationAction(Request $request)
    {
        if (is_null($query = $request->get(self::PARAM_QUERY))) {
            return array(
                'query' => $query,
                'result' => false
            );
        } else {
            $this->stopwatch = new Stopwatch();

            $this->stopwatch->start('solr');
            $solrResult = $this->solrProductRepository->findByFulltext(
                $query,
                (int)$request->get(self::PARAM_LIMIT, self::PARAM_LIMIT_DEFAULT),
                (int)$request->get(self::PARAM_OFFSET, self::PARAM_OFFSET_DEFAULT)
            );
            $solrTime = $this->stopwatch->stop('solr');

            $this->stopwatch->start('doctrine');
            $doctrineResult = $this->doctrineProductRepository->findByFulltext(
                $query,
                (int)$request->get(self::PARAM_LIMIT, self::PARAM_LIMIT_DEFAULT),
                (int)$request->get(self::PARAM_OFFSET, self::PARAM_OFFSET_DEFAULT)
            );
            $doctrineTime = $this->stopwatch->stop('doctrine');

            return array(
                'query' => $query,
                'timing' => array(
                    'solr' => $solrTime,
                    'mysql' => $doctrineTime
                ),
                'result' => array(
                    'solr' => $solrResult,
                    'mysql' => $doctrineResult
                )
            );
        }
    }
} 