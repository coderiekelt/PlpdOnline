<?php

namespace AppBundle\Controller;

use Abbert\Datagrid\Column\ActionColumn;
use Abbert\Datagrid\Datagrid;
use Abbert\Datagrid\Datasource\DoctrineSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class InsightsController
 * @package AppBundle\Controller
 * @Route("/insights")
 */
class InsightsController extends Controller
{
    /**
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted("ROLE_SUPERVISOR");

        $em = $this->getDoctrine()->getManager();

        $inactivityRepo = $em->getRepository('AppBundle:Inactivity');
        $commendationRepo = $em->getRepository('AppBundle:Commendation');
        $infractionRepo = $em->getRepository('AppBundle:Infraction');

        $activityGrid = new Datagrid();
        $activityGrid->setId("activityGrid");
        $commendationGrid = new Datagrid();
        $commendationGrid->setId("commendationsGrid");
        $infractionGrid = new Datagrid();
        $infractionGrid->setId("infractionsGrid");

        $activitySource = new DoctrineSource($inactivityRepo);
        $commendationSource = new DoctrineSource($commendationRepo);
        $infractionSource = new DoctrineSource($infractionRepo);

        $activityGrid->setDatasource($activitySource);
        $commendationGrid->setDatasource($commendationSource);
        $infractionGrid->setDatasource($infractionSource);

        $searchActivity = $activityGrid->getRequest()->get('search');
        if (!empty($search['value'])) {
            $activitySource->getQueryBuilder()->leftJoin('t.supervisor', 'k')->andWhere(
                't.reason LIKE :query OR k.name LIKE :query OR t.datetime LIKE :query'
            );
            $activitySource->getQueryBuilder()->setParameter('query', '%' . $search['value'] . '%');
        }

        $searchCommendation = $commendationGrid->getRequest()->get('search');
        if (!empty($search['value'])) {
            $commendationSource->getQueryBuilder()->andWhere(
                't.reason LIKE :query OR t.startdate LIKE :query OR t.enddate LIKE :query'
            );
            $commendationSource->getQueryBuilder()->setParameter('query', '%' . $search['value'] . '%');
        }

        $searchInfraction = $infractionGrid->getRequest()->get('search');
        if (!empty($search['value'])) {
            $infractionSource->getQueryBuilder()->leftJoin('t.supervisor', 'k')->andWhere(
                't.reason LIKE :query OR k.name LIKE :query OR t.datetime LIKE :query'
            );
            $infractionSource->getQueryBuilder()->setParameter('query', '%' . $search['value'] . '%');
        }

        $infractionSource->getQueryBuilder()->orderBy("t.datetime", "DESC");
        $commendationSource->getQueryBuilder()->orderBy("t.datetime", "DESC");
        $activitySource->getQueryBuilder()->orderBy("t.datetime", "DESC");

        $activityGrid->addColumn("Employee", function($row) {
            return $row->getEmployee()->getUsername();
        });

        $activityGrid->addColumn("Reason", function($row) {
            return $row->getReason();
        });

        $activityGrid->addColumn("Start date", function($row) {
            return $row->getStartdate()->format("Y-m-d");
        });

        $activityGrid->addColumn("End date", function($row) {
            return $row->getEnddate()->format("Y-m-d");
        });

        $activityGrid->addColumn("Date added", function($row) {
            return $row->getDatetime()->format("Y-m-d");
        });

        $activityGrid->addColumn('Actions', new ActionColumn(function ($row) {
            return array(
                array(
                    'label' => 'dg-icon:fa fa-user fa-fw',
                    'href' => '/employees/view/' . $row->getEmployee()->getId(),
                    'class' => 'btn-xs btn-primary'
                )
            );
        }));

        $infractionGrid->addColumn("Employee", function($row) {
            return $row->getEmployee()->getUsername();
        });

        $infractionGrid->addColumn("Type", function($row) {
            return $row->getType();
        });

        $infractionGrid->addColumn("Reason", function($row) {
            return $row->getReason();
        });

        $infractionGrid->addColumn("Supervisor", function($row) {
            return $row->getSupervisor()->getUsername();
        });

        $infractionGrid->addColumn("Date added", function($row) {
            return $row->getDatetime()->format("Y-m-d");
        });

        $infractionGrid->addColumn('Actions', new ActionColumn(function ($row) {
            return array(
                array(
                    'label' => 'dg-icon:fa fa-user fa-fw',
                    'href' => '/employees/view/' . $row->getEmployee()->getId(),
                    'class' => 'btn-xs btn-primary'
                ),
                array(
                    'label' => 'dg-icon:fa fa-shield fa-fw',
                    'href' => '/employees/view/' . $row->getSupervisor()->getId(),
                    'class' => 'btn-xs btn-primary'
                ),
                array(
                    'label' => 'dg-icon:fa fa-arrow-right fa-fw',
                    'href' => $row->getThread(),
                    'class' => 'btn-xs btn-info'
                )
            );
        }));

        $commendationGrid->addColumn("Employee", function($row) {
            return $row->getEmployee()->getUsername();
        });

        $commendationGrid->addColumn("Reason", function($row) {
            return $row->getReason();
        });

        $commendationGrid->addColumn("Supervisor", function($row) {
            return $row->getSupervisor()->getUsername();
        });

        $commendationGrid->addColumn("Date added", function($row) {
            return $row->getDatetime()->format("Y-m-d");
        });

        $commendationGrid->addColumn('Actions', new ActionColumn(function ($row) {
            return array(
                array(
                    'label' => 'dg-icon:fa fa-user fa-fw',
                    'href' => '/employees/view/' . $row->getEmployee()->getId(),
                    'class' => 'btn-xs btn-primary'
                ),
                array(
                    'label' => 'dg-icon:fa fa-shield fa-fw',
                    'href' => '/employees/view/' . $row->getSupervisor()->getId(),
                    'class' => 'btn-xs btn-primary'
                )
            );
        }));

        $infractionRender = $infractionGrid->render();
        $commendationRender = $commendationGrid->render();
        $activityRender = $activityGrid->render();


        return $this->render('@App/Insights/index.html.twig', [
            'activity' => $activityRender->renderHtml(),
            'commendation' => $commendationRender->renderHtml(),
            'infraction' => $infractionGrid->renderHtml()
        ]);
    }
}
