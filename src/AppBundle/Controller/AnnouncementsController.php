<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Announcement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AnnoucementsController
 * @package AppBundle\Controller
 * @Route("/announcements")
 */
class AnnouncementsController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('AppBundle:Announcements:index.html.twig', array(
            'announcements' => $em->getRepository('AppBundle:Announcement')->findAll()
        ));
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_COMMAND');

        $em = $this->getDoctrine()->getManager();

        $announcement = new Announcement();

        $form = $this->createFormBuilder($announcement)
            ->add('title')
            ->add('body', TextareaType::class)
            ->getForm();

        if ($form->handleRequest($request)->isValid())
        {
            $announcement->setDatetime(new \DateTime());

            $em->persist($announcement);
            $em->flush();
        }

        return $this->render('AppBundle:Announcements:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
