<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Controller;

use FS\SolrBundle\Doctrine\Hydration\HydrationModes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;

class HydratingSearchController extends Controller
{
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

            $stopwatch->start('solr');
            $repo = $this->get('solr.repository.product');
            $solrResult = $repo->findByFulltext($query);
            $solrTime = $stopwatch->stop('solr');

            return array(
                'query' => $query,
                'timing' => array(
                    'solr' => $solrTime,
                ),
                'result' => array(
                    'solr' => $solrResult
                )
            );
        }
    }
}
