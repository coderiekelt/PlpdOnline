<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Shout;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShoutboxController
 * @package AppBundle\Controller
 * @Route("/shoutbox")
 */
class ShoutboxController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Shoutbox:index.html.twig', array(
            // ...
        ));
    }


    /**
     * @Route("/messages")
     */
    public function messagesAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Shout');

        $num = $repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $messages = $repo->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.datetime', 'ASC')
            ->setMaxResults(8)
            ->setFirstResult($num - 8)
            ->getQuery()
            ->getResult();

        return $this->render('AppBundle:Shoutbox:messages.html.twig', array(
            'messages' => $messages
        ));
    }

    /**
     * @Route("/post")
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $shout = new Shout();
        $shout->setMessage($request->request->get('message'));
        $shout->setDatetime(new \DateTime());
        $shout->setUser($this->get('security.token_storage')->getToken()->getUser());

        $em->persist($shout);
        $em->flush();

        return new Response("ok");
    }

}
