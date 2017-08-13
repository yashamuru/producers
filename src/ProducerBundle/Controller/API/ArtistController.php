<?php

namespace ProducerBundle\Controller\API;

use ProducerBundle\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArtistController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/api/artists")
     */
    public function listAction()
    {
        $artists = $this->getDoctrine()->getRepository(Artist::class)->findAll();

        return $this->respond($artists);
    }

    /**
     * @Method("GET")
     * @Route("/api/artists/{id}")
     */
    public function showAction($id)
    {
        $artist = $this->getDoctrine()->getRepository(Artist::class)->find($id);

        return $this->respond([$artist]);
    }

    /**
     * @Method("POST")
     * @Route("/api/artists/")
     */
    public function createAction(Request $request)
    {
        $params = json_decode($request->getContent(), true);
        $artist = Artist::createFromParameters(
            $params['name'] ?? null,
            $params['instrument'] ?? null
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($artist);
        $em->flush();

        return $this->respond([$artist]);
    }

    private function respond(array $artists): JsonResponse
    {
        $res = [];
        foreach($artists as $artist)
        {
            if ($artist) {
                /** @var Artist $artist */
                $res[] = [
                    'id' => $artist->getId(),
                    'name' => $artist->getName(),
                    'instrument'=> $artist->getInstrument()
                ];
            }
        }
        return new JsonResponse($res);
    }

    /**
     * @Method("PUT")
     * @Route("/api/artists/{id}")
     */
    public function editAction($id, Request $request)
    {
        $existingArtist = $this->getDoctrine()->getRepository(Artist::class)->find($id);

        $params = json_decode($request->getContent(), true);
        $artist = Artist::createFromParameters(
            $params['name'] ?? null,
            $params['instrument'] ?? null
        );

        $existingArtist->setName($artist->getName())->setInstrument($artist->getInstrument());

        $em = $this->getDoctrine()->getManager();
        $em->persist($existingArtist);
        $em->flush();

        return $this->respond([$existingArtist]);
    }
}
