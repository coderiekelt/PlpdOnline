<?php

namespace AcademyBundle\Controller;

use Abbert\Datagrid\Column\ActionColumn;
use Abbert\Datagrid\Datagrid;
use Abbert\Datagrid\Datasource\DoctrineSource;
use AppBundle\Entity\User;
use CareerBundle\Entity\Application;
use CareerBundle\Entity\ApplicationStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApplicationsController
 * @package AppBundle\Controller
 * @Route("/applications")
 */
class ApplicationsController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $applicationsGrid = new Datagrid();
        $applicationsSource = new DoctrineSource($this->getDoctrine()->getRepository('CareerBundle:Application'));

        $applicationsGrid->setDatasource($applicationsSource);

        $applicationsGrid->addColumn("Forum Name", function($row) {
            return $row->getForumname();
        });
        $applicationsGrid->addColumn("IC Name", function($row) {
            return $row->getRpname();
        });
        $applicationsGrid->addColumn("Steam Name", function($row) {
            return $row->getSteamname();
        });
        $applicationsGrid->addColumn("Steam ID", function($row) {
            return $row->getSteamid();
        });
        $applicationsGrid->addColumn("Submitted", function($row) {
            return $row->getTimestamp()->format('Y-m-d H:i:s');
        });
        $applicationsGrid->addColumn('Actions', new ActionColumn(function ($row) {
            return array(
                array(
                    'label' => 'dg-icon:fa fa-pencil',
                    'href' => '/applications/review/' . $row->getId(),
                ),
                array(
                    'class' => 'btn-danger',
                    'label' => 'dg-icon:fa fa-close',
                    'href' => '/applications/remove/' . $row->getId(),
                )
            );
        }));

        $applicationsSource->getQueryBuilder()->orderBy("t.timestamp", "DESC");
        $applicationsSource->getQueryBuilder()->where('t.completed = \'yes\'');

        $search = $applicationsGrid->getRequest()->get('search');
        if (!empty($search['value'])) {
            $applicationsSource->getQueryBuilder()->andWhere(
                't.name LIKE :query OR t.username LIKE :query OR t.divisions LIKE :query OR t.rank LIKE :query'
            );
            $applicationsSource->getQueryBuilder()->setParameter('query', '%' . $search['value'] . '%');
        }

        $grid = $applicationsGrid->render();

        return $this->render('AcademyBundle:Applications:index.html.twig', array(
            'grid' => $grid,
            'status' => $this->getApplicationStatus()
        ));
    }

    /**
     * @Route("/review/{application}")
     */
    public function reviewAction(Application $application)
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        return $this->render('AcademyBundle:Applications:review.html.twig', array(
            'application' => $application
        ));
    }

    private function getApplicationStatus()
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $latest = $em->getRepository('CareerBundle:ApplicationStatus')
            ->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];

        if (isset($latest) && $latest->getStatus() == "open") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Route("/accept/{application}")
     */
    public function acceptAction(Application $application)
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $application->setCompleted("accepted");

        $password = uniqid();

        // Create user

        $user = new User();
        $user->setUsername($application->getForumname());
        $user->setEmail($application->getEmail());
        $user->setSteamid($application->getSteamid());
        $user->setName($application->getRpname());
        $user->setDivisions("Patrol");
        $user->setRank("Applicant");
        $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $password));
        $user->setEnabled(false);
        $user->setViews(0);
        $user->setAdded(new \DateTime());

        $em->persist($user);

        $em->flush();

        return $this->render('@Academy/Applications/accept.html.twig', ['username' => $application->getForumname(), 'password' => $password]);
    }

    /**
     * @Route("/deny/{application}")
     */
    public function denyAction(Application $application)
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $application->setCompleted("denied");
        $em->flush();

        return $this->redirectToRoute("academy_applications_index");
    }

    /**
     * @Route("/assign/{application}/{pto}")
     */
    public function assignAction(Application $application, User $pto)
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $application->setPto($pto);
        $em->flush();

        return $this->redirectToRoute("academy_applications_review", ['application' => $application->getId()]);
    }

    /**
     * @Route("/remove/{application}")
     */
    public function removeAction(Application $application)
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($application);
        $em->flush();

        return $this->redirectToRoute("academy_applications_index");
    }

    /**
     * @Route("/close")
     */
    public function closeAction()
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $status = new ApplicationStatus();
        $status->setStatus("closed");

        $em->persist($status);
        $em->flush();

        return $this->redirectToRoute("academy_applications_index");
    }

    /**
     * @Route("/open")
     */
    public function openAction()
    {
        if (!$this->get('security.token_storage')->getToken()->getUser()->isPoliceTrainingOfficer())
        {
            $this->denyAccessUnlessGranted("ROLE_COMMAND");
        }

        $em = $this->getDoctrine()->getManager();

        $status = new ApplicationStatus();
        $status->setStatus("open");

        $em->persist($status);
        $em->flush();

        return $this->redirectToRoute("academy_applications_index");
    }

}
