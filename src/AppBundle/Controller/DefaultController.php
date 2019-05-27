<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $validator = $errors = $this->get('validator');
        $emailConstraint = new EmailConstraint();

        // Check if valid email
        $emailsErrors = $validator->validate($user->getEmail(), $emailConstraint);


        if (count($emailsErrors) > 0)
        {
            $form = $this->createFormBuilder($user)
                ->add("forum", TextType::class, ["label" => "Forum name", "required" => true, 'data' => ''])
                ->add("name", TextType::class, ["label" => "Roleplay name", "required" => true, 'data' => ''])
                ->add("email", EmailType::class, ["label" => "Email address", "required" => true, 'data' => ''])
                ->getForm();

            if ($form->handleRequest($request)->isValid())
            {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute("dashboard");
            }

            return $this->render('@App/setup.html.twig', [
                'form' => $form->createView()
            ]);
        }

        // replace this example code with whatever you need
        return $this->render('@App/dashboard.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}
