<?php

namespace Frne\Bundle\SolrSearchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ArticleController extends RepositoryAwareController
{
    /**
     * @Template()
     *
     * @param string $sortBy
     * @return array
     */
    public function listAction($sortBy)
    {
        $this->stopwatch->start('doctrine');
        $doctrineResult = $this->doctrineArticleRepository
            ->findAllSortBy($sortBy);
        $doctrineTime = $this->stopwatch->stop('doctrine');

        $this->stopwatch->start('solr');
        $solrResult = $this->solrArticleRepository
            ->findAllSortBy($sortBy);
        $solrTime = $this->stopwatch->stop('solr');

        return array(
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