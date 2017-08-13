<?php

namespace ProducerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Artist
 *
 * @ORM\Table(name="artist")
 * @ORM\Entity(repositoryClass="ProducerBundle\Repository\ArtistRepository")
 */
class Artist
{
    public static function createFromParameters(
        $name,
        $instrument
    ) {
        Assert::string($name);
        Assert::string($instrument);

        $artist = new self();
        $artist->setName($name)->setInstrument($instrument);
        return $artist;
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="instrument", type="string", length=255)
     */
    private $instrument;

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
    public function setId(int $id): Artist
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): Artist
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstrument(): string
    {
        return $this->instrument;
    }

    /**
     * @param string $instrument
     */
    public function setInstrument(string $instrument): Artist
    {
        $this->instrument = $instrument;
        return $this;
    }
}