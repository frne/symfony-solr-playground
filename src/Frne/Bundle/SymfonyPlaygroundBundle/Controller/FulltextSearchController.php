<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Controller;

use FS\SolrBundle\Doctrine\Hydration\HydrationModes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;

class FulltextSearchController extends Controller
{
    const FETCH_MAX_RESULTS = 20;

    const FETCH_OFFSET = 0;

    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if (is_null($query = $request->get('q'))) {
            return array(
                'query' => $query,
                'result' => false
            );
        } else {
            $stopwatch = new Stopwatch();

            $stopwatch->start('mysql');
            $repo = $this->get('doctrine.repository.article');
            $doctrineResult = $repo->findByFulltext($query, self::FETCH_MAX_RESULTS, self::FETCH_OFFSET);
            $mysqlTime = $stopwatch->stop('mysql');

            $stopwatch->start('solr');
            $repo = $this->get('solr.repository.article');
            $solrResult = $repo->findByFulltext($query, self::FETCH_MAX_RESULTS, self::FETCH_OFFSET);
            $solrTime = $stopwatch->stop('solr');

            return array(
                'query' => $query,
                'timing' => array(
                    'solr' => $solrTime,
                    'mysql' => $mysqlTime
                ),
                'result' => array(
                    'solr' => $solrResult,
                    'mysql' => $doctrineResult
                )
            );
        }
    }
}
