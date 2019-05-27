<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commendation;
use AppBundle\Entity\Inactivity;
use AppBundle\Entity\Infraction;
use AppBundle\Entity\Note;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AjaxController
 * @package AppBundle\Controller
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
    /**
     * @Route("/note/{id}")
     */
    public function noteAction(User $user, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR');

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($user->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $super = $this->get('security.token_storage')->getToken()->getUser();

        $note = new Note();
        $note->setReason($request->request->get("reason"));
        $note->setEmployee($user);
        $note->setSupervisor($super);
        $note->setDatetime(new \DateTime());

        $em->persist($note);
        $em->flush();

        return new Response("ok");
    }

    /**
     * @Route("/password/{id}")
     */
    public function passwordAction(User $user, Request $request)
    {
        // 0.1.9 : Users may now change their own password through a prettier page

        if ($user->getId() != $this->get('security.token_storage')->getToken()->getUser()->getId()) {
            $this->denyAccessUnlessGranted('ROLE_COMMAND');
        }

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($user->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();

        if ($request->request->get("password") == "random")
        {
            $password = uniqid() . uniqid();
        } else {
            $password = $request->request->get('password');
        }

        $encrypt = $this->get('security.password_encoder');
        $user->setPassword($encrypt->encodePassword($user, $password));

        $em->flush();

        return new Response($password);
    }

    /**
     * @Route("/delete/note/{id}")
     */
    public function deleteNoteAction(Note $commendation, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_COMMAND');

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($commendation->getEmployee()->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $super = $this->get('security.token_storage')->getToken()->getUser();

        $em->remove($commendation);
        $em->flush(); // Down the toilet with you

        return $this->redirectToRoute("app_employees_view", ['id' => $commendation->getEmployee()->getId()]);
    }


    /**
     * @Route("/delete/infraction/{id}")
     */
    public function deleteInfractionAction(Infraction $commendation, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_COMMAND');

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($commendation->getEmployee()->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $super = $this->get('security.token_storage')->getToken()->getUser();

        $em->remove($commendation);
        $em->flush(); // Down the toilet with you

        return $this->redirectToRoute("app_employees_view", ['id' => $commendation->getEmployee()->getId()]);
    }

    /**
     * @Route("/delete/commendation/{id}")
     */
    public function deleteCommendationAction(Commendation $commendation, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_COMMAND');

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($commendation->getEmployee()->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $super = $this->get('security.token_storage')->getToken()->getUser();

        $em->remove($commendation);
        $em->flush(); // Down the toilet with you

        return $this->redirectToRoute("app_employees_view", ['id' => $commendation->getEmployee()->getId()]);
    }

    /**
     * @Route("/commendation/{id}")
     */
    public function commendationAction(User $user, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR');

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($user->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $super = $this->get('security.token_storage')->getToken()->getUser();

        $note = new Commendation();
        $note->setReason($request->request->get("reason"));
        $note->setEmployee($user);
        $note->setSupervisor($super);
        $note->setDatetime(new \DateTime());

        $em->persist($note);
        $em->flush();

        // Create notification
        $notification = new Notification();
        $notification->setContent("You have received a new commendation on your record.");
        $notification->setUrl("/employees/view/" . $user->getId() . "/commendations");
        $notification->setEmployee($user);
        $notification->setDatetime(new \DateTime());
        $notification->setSeen("no");

        $em->persist($notification);
        $em->flush();

        return new Response("ok");
    }

    /**
     * @Route("/infraction/{id}")
     */
    public function infractionAction(User $user, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR');

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($user->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();
        $super = $this->get('security.token_storage')->getToken()->getUser();

        $note = new Infraction();
        $note->setReason($request->request->get("reason"));
        $note->setThread($request->request->get("thread"));
        $note->setType($request->request->get("type"));
        $note->setEmployee($user);
        $note->setSupervisor($super);
        $note->setDatetime(new \DateTime());

        $em->persist($note);
        $em->flush();

        // Create notification
        $notification = new Notification();
        $notification->setContent("You have received a new infraction on your record.");
        $notification->setUrl("/employees/view/" . $user->getId() . "/infractions");
        $notification->setEmployee($user);
        $notification->setDatetime(new \DateTime());
        $notification->setSeen("no");

        $em->persist($notification);
        $em->flush();

        return new Response("ok");
    }

    /**
     * @Route("/inactivity/{id}")
     */
    public function inactivityAction(User $user, Request $request)
    {
        if ($user->getId() != $this->get('security.token_storage')->getToken()->getUser()->getId())
        {
            $this->denyAccessUnlessGranted("ROLE_SUPERVISOR");
        }

        $restricted = [
            "Riekelt",
            "Chrissy",
            "Jordan",
            "Chris"
        ];

        foreach ($restricted as $entry)
        {
            if ($user->getUsername() == $entry && $this->get('security.token_storage')->getToken()->getUsername() != $entry)
            {
                return new Response("nope");
            }
        }

        $em = $this->getDoctrine()->getManager();

        $start = \DateTime::createFromFormat("m/d/Y", $request->request->get('start'));
        $end = \DateTime::createFromFormat("m/d/Y", $request->request->get('end'));

        $inactivity = new Inactivity();
        $inactivity->setEmployee($user);
        $inactivity->setReason($request->request->get('reason'));
        $inactivity->setStartdate($start);
        $inactivity->setEnddate($end);
        $inactivity->setDatetime(new \DateTime());

        $em->persist($inactivity);
        $em->flush();

        return new Response("ok");
    }
}
