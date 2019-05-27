<?php

namespace ApplicantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class PatrolController
 * @package ApplicantBundle\Controller
 * @Route("/patrol")
 */
class PatrolController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        return $this->render('ApplicantBundle:Patrol:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/schedule")
     */
    public function scheduleAction()
    {
        return $this->render('ApplicantBundle:Patrol:schedule.html.twig', array(
            // ...
        ));
    }

}
