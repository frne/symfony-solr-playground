<?php

namespace Frne\Bundle\SymfonyPlaygroundBundle\Controller;

use FS\SolrBundle\Doctrine\Hydration\HydrationModes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Stopwatch\Stopwatch;

class SearchController extends Controller
{
    const FETCH_MAX_RESULTS = 10000;

    const ARTICLE_ENTITY_NAME = 'FrneSymfonyPlaygroundBundle:Article';

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
            $em = $this->get('doctrine.orm.entity_manager');
            $doctrineResult = $em->getRepository(self::ARTICLE_ENTITY_NAME)->createQueryBuilder('a')
                ->where('a.id LIKE :query')
                ->orWhere('a.title LIKE :query')
                ->orWhere('a.author LIKE :query')
                ->orWhere('a.content LIKE :query')
                ->setMaxResults(self::FETCH_MAX_RESULTS)
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();
            $mysqlTime = $stopwatch->stop('mysql');

            var_dump($mysqlTime->getDuration());

            $stopwatch->start('solr');
            $solrQuery = $this->get('solr.client.default')->createQuery(self::ARTICLE_ENTITY_NAME);
            $solrQuery->setOptions(array('rows' => self::FETCH_MAX_RESULTS));
            $solrQuery->setHydrationMode(HydrationModes::HYDRATE_INDEX);
            $solrQuery->queryAllFields($query);

            $solrResult = $solrQuery->getResult();

            $solrTime = $stopwatch->stop('solr');

            var_dump($solrTime->getDuration());


            return array(
                'query' => $query,
                'result' => array(
                    'mysql' => $doctrineResult,
                    'solr' => $solrResult
                )
            );
        }


    }
}
