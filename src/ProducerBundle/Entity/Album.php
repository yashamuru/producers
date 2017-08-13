<?php

namespace ProducerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Album
 *
 * @ORM\Table(name="album")
 * @ORM\Entity(repositoryClass="ProducerBundle\Repository\AlbumRepository")
 */
class Album
{

    public static function createFromParameters(
        $name,
        $datePublished
    ) {
        Assert::string($name);
        $datePublished = new \DateTime($datePublished);
        Assert::isInstanceOf(
            $datePublished,
            \DateTime::class,
            "%s should be a valid DateTime");

        $album = new self();
        $album->setName($name)->setDatePublished($datePublished);
        return $album;
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_published", type="datetime")
     */
    private $datePublished;

    /**
     * @ORM\OneToMany(targetEntity="AlbumParticipation", mappedBy="album", cascade={"all"}, orphanRemoval=true)
     */
    private $participations;

    public function __construct() {
        $this->participations = new ArrayCollection();
    }

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
    public function setId(int $id): Album
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
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatePublished(): \DateTime
    {
        return $this->datePublished;
    }

    /**
     * @param \DateTime $datePublished
     */
    public function setDatePublished(\DateTime $datePublished)
    {
        $this->datePublished = $datePublished;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    public function clearParticipations()
    {
        $this->participations->clear();
        return $this;
    }

    public function addParticipation(AlbumParticipation $participation)
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
        }

        return $this;
    }

    public function removeParticipation(AlbumParticipation $participation)
    {
        $this->participations->removeElement($participation);
        return $this;
    }
}