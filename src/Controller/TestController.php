<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{

    /**
     * @Route("/test1", name="test1")
     * L'appel de cette route retourne une réponse, qui sera affiché
     */
    public function index(): Response
    {
        return new Response("Ceci retourne un texte");
    }

    /**
     * @Route("/dump_request", name="dump_request")
     * On regarde comment fonctionne Request
     */
    public function dumpRequest1(): Response
    {
        // Request permet de chopper les variable globaux tout ça...
        // En gros, ça permet de récupérer ce qui passe dans la requete https
        $request = Request::createFromGlobals();
        // dd correspond à die(dump(...))
        // dump() est mieux que var_dump(), ça rend plus jolie
        dd($request);
    }

    /**
     * @Route("/dump_request2", name="dump")
     * Une autre façon d'initialiser Request
     */
    public function dumpRequest2(Request $request): Response
    {
        dd($request);
    }

    /**
     * @Route("/test2age", name="test2age")
     * Requete sur https://127.0.0.1:8000/test/test2age?age=33
     * Le but est de recupérer le parametre age
     */
    public function index1(Request $request): Response
    {
        dump($request);
        $age = $request->query->get('age', 99);
        return new Response("Vous avez $age ans");
    }

    /**
     * @Route("/test2/{age?5}", name="test3", requirements={"age": "\d+"})
     * Requete sur https://127.0.0.1:8000/test/test2/22
     * En gros notre route prend un parametre qui sera récupéré par Symfony (grace a un truc appelé Argument Resolver)
     * Et bim on affiche le parametre récupérer par la fonction, magie
     * Le requirements c'est du bonus, on indique juste que age est forcement au format numérique
     */
    public function index2(Request $request, $age): Response
    {
        return new Response("Vous avez $age ans");
    }

    public function index3(Request $request, $age): Response
    {
        return new Response("Vous avez $age ans");
    }
}
