<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class NotificationsController
 * @package AppBundle\Controller
 * @Route("/notifications")
 */
class NotificationsController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/")
     */
    public function indexAction()
    {
        $user = $this->get("security.token_storage")->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('AppBundle:Notification')
            ->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.datetime', 'DESC')
            ->where("t.employee = '" . $user->getId() . "'")
            ->getQuery()
            ->getResult();

        foreach ($notifications as $notification)
        {
            $notification->setSeen(time());

            $em->flush();
        }

        return $this->render('@App/Notifications/index.html.twig', ['notifications' => $notifications]);
    }
}
