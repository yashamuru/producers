<?php

namespace ProducerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ProducerBundle\Repository\AlbumParticipationRepository;

/**
 * AlbumParticipation
 *
 * @ORM\Table(name="album_participation")
 * @ORM\Entity(repositoryClass="ProducerBundle\Repository\AlbumParticipationRepository")
 */
class AlbumParticipation
{

    public static function createByParameters(
        Album $album,
        array $artists
    ) {
        $result = new ArrayCollection();
        foreach($artists as $artist)
        {
            $participation = new self();
            $participation->setAlbum($album)->setArtist($artist);

            /** @var Artist $artist */
            $result->add($participation);
        }
        return $result;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="participations")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */
    private $album;

    /**
     * @ORM\ManyToOne(targetEntity="Artist")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): AlbumParticipation
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album): AlbumParticipation
    {
        $this->album = $album;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param mixed $artist
     */
    public function setArtist($artist): AlbumParticipation
    {
        $this->artist = $artist;
        return $this;
    }
}