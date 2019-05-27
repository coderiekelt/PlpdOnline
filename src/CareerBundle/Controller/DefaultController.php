<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LightOpenID;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('CareerBundle:Default:index.html.twig', ['status' => $this->getApplicationStatus()]);
    }

    public function startAction()
    {
        // TODO: Generate steam URL for step one
    }

    private function getApplicationStatus()
    {
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

    private function toSteamID($id) {
        if (is_numeric($id) && strlen($id) >= 16) {
            $z = bcdiv(bcsub($id, '76561197960265728'), '2');
        } elseif (is_numeric($id)) {
            $z = bcdiv($id, '2'); // Actually new User ID format
        } else {
            return $id; // We have no idea what this is, so just return it.
        }
        $y = bcmod($id, '2');
        return 'STEAM_0:' . $y . ':' . floor($z);
    }

    /**
     * @param $token
     * @param Request $request
     * @Route("/apply/{token}")
     */
    public function applyAction($token, Request $request)
    {
        if ($this->getApplicationStatus() == false)
        {
            return new Response("Sorry, applications are closed.");
        }

        $em = $this->getDoctrine()->getManager();

        $application = $em->getRepository("CareerBundle:Application")->findOneBy(['token' => $token]);

        if (!isset($application) || $application->getCompleted() == "yes")
        {
            return new Response("Invalid token.");
        }

        $form = $this->createFormBuilder($application)
            ->add("forumname", TextType::class)
            ->add("steamname", TextType::class, ['disabled' => true])
            ->add("steamid", TextType::class, ['disabled' => true])
            ->add("rpname", TextType::class)
            ->add("country", CountryType::class)
            ->add("dob", BirthdayType::class)
            ->add("microphone", ChoiceType::class, ['choices' => ['Yes' => 'Yes', 'No' => 'No'], 'expanded' => true, 'multiple' => false])
            ->add("reasonApply", TextareaType::class)
            ->add("reasonChoose", TextareaType::class)
            ->add("skills", TextareaType::class)
            ->add("experience", TextareaType::class)
            ->add("email", TextType::class)
            ->add("concerns", TextareaType::class, ['required' => false])
            ->add("information", TextareaType::class, ['required' => false])
            ->getForm();

        if ($form->handleRequest($request)->isValid())
        {
            $dob = $request->request->get('form')['dob'];
            $dob = $dob['day'] . " - " . $dob['month'] . " - " . $dob['year'];

            $application->setDob($dob);

            $application->setCompleted("yes");
            $em->flush();

            return $this->render("@Career/Default/success.html.twig");
        }

        return $this->render('@Career/Default/apply.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param $steam
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/steam/{steam}")
     */
    public function steamAction($steam = null, Request $request)
    {
        if ($this->getApplicationStatus() == false)
        {
            return new Response("Sorry, applications are closed.");
        }

        $em = $this->getDoctrine()->getManager();

        $openId = new \LightOpenID($this->getParameter('steam_redirect'));

        $openId->identity = "http://steamcommunity.com/openid";

        if (!$openId->mode) {
            return $this->redirect($openId->authUrl());
        } else {
            if ($openId->validate())
            {
                $steamId = explode("/", $openId->identity)[5];

                $apiKey = "449ABD33E84C91F3B37EB4AA2D2A004A";

                $url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$apiKey."&steamids=" . $steamId);
                $content = json_decode($url, true)["response"]["players"][0];

                $steamIdDB = $this->toSteamID($steamId);
                $steamName = $content["personaname"];

                $appToken = uniqid() . rand(1111, 9999) . $steamId . uniqid();

                $app = new Application();
                $app->setToken($appToken);
                $app->setSteamid($steamIdDB);
                $app->setSteamname($steamName);
                $app->setTimestamp(new \DateTime());
                $app->setCompleted("no");

                $em->persist($app);
                $em->flush();

                return $this->redirectToRoute("career_default_apply", ['token' => $appToken]);
            } else {
                return new Response("Fatal error while connecting to Steam, please try again later.");
            }
        }
    }

}
