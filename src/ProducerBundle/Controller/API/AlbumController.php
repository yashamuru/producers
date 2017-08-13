<?php

namespace ProducerBundle\Controller\API;

use Doctrine\Common\Collections\ArrayCollection;
use ProducerBundle\Entity\Album;
use ProducerBundle\Entity\AlbumParticipation;
use ProducerBundle\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AlbumController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/api/albums")
     */
    public function listAction()
    {
        $albums = $this->getDoctrine()->getRepository(Album::class)->findAll();

        return $this->respond($albums);
    }

    /**
     * @Method("GET")
     * @Route("/api/albums/{id}")
     */
    public function showAction($id)
    {
        $album = $this->getDoctrine()->getRepository(Album::class)->find($id);

        return $this->respond([$album]);
    }

    /**
     * @Method("POST")
     * @Route("/api/albums/")
     */
    public function createAction(Request $request)
    {
        $params = json_decode($request->getContent(), true);
        $album = Album::createFromParameters(
            $params['name'] ?? null,
            $params['datePublished'] ?? null
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($album);

        if (! empty($params['artists']))
        {
            $artists = $this->getDoctrine()->getRepository(Artist::class)
                ->findBy(["id" => $params['artists']]);

            $participations = AlbumParticipation::createByParameters($album, $artists);
            foreach($participations as $item)
            {
                $album->addParticipation($item);
            }
        }

        $em->flush();

        return $this->respond([$album]);
    }

    private function respond(array $albums): JsonResponse
    {
        $res = [];
        foreach($albums as $album)
        {
            if ($album) {
                /** @var Album $album */
                $res[] = [
                    'id' => $album->getId(),
                    'name' => $album->getName(),
                    'datePublished'=> $album->getDatePublished()->format('Y-m-d'),
                    'artists' => array_map(function (AlbumParticipation $participation) {
                        $artist = $participation->getArtist();
                        return ['id'=> $artist->getId(), 'name' => $artist->getName(), 'instrument'=> $artist->getInstrument()];
                    }, $album->getParticipations()->toArray())
                ];
            }
        }
        return new JsonResponse($res);
    }

    /**
     * @Method("PUT")
     * @Route("/api/albums/{id}")
     */
    public function editAction($id, Request $request)
    {
        $existingAlbum = $this->getDoctrine()->getRepository(Album::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $params = json_decode($request->getContent(), true);
        $album = Album::createFromParameters(
            $params['name'] ?? null,
            $params['datePublished'] ?? null
        );

        $existingAlbum->setName($album->getName())->setDatePublished($album->getDatePublished());

        $existingAlbum->clearParticipations();
        $em->persist($existingAlbum);

        if (! empty($params['artists']))
        {
            $artists = $this->getDoctrine()->getRepository(Artist::class)
                ->findBy(["id" => $params['artists']]);

            $participations = AlbumParticipation::createByParameters($existingAlbum, $artists);
            foreach($participations as $item) {
                $existingAlbum->addParticipation($item);
            }
        }


        $em->persist($existingAlbum);
        $em->flush();

        return $this->respond([$existingAlbum]);
    }
}
