<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class SteamController
 * @package AppBundle\Controller
 * @Route("/steam")
 */
class SteamController extends Controller
{
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
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $openId = new \LightOpenID('http://plpd.online/steam');

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

                $user = $em->getRepository('AppBundle:User')->findOneBy(['steamid' => $steamIdDB]);

                // Never done this before, so lets try this.
                $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());

                $context = $this->get('security.token_storage');
                $context->setToken($token);

                return $this->redirectToRoute("dashboard");
            } else {
                return new Response("Fatal error while connecting to Steam, please try again later.");
            }
        }
    }
}
