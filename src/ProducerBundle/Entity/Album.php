<?php

namespace ProducerBundle\Entity;

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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
}