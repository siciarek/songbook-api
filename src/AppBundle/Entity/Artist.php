<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * AppBundle\Entity\Country
 *
 * @ORM\Entity
 * @ORM\Table(name="artist")
 * @ORM\Entity(repositoryClass="ArtistRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Artist
{

    use ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\SoftDeletable\SoftDeletable;

    public function __toString()
    {
        $string = '?';

        $parts = array_filter([
            $this->getFirstName(),
            $this->getLastName(),
            $this->getName(),
        ]);

        if (count($parts) > 0) {
            $string = implode(' ', $parts);
        }

        return $string;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(nullable=true)
     */
    private $name;

    /**
     * @ORM\Column()
     * @Serializer\SerializedName("firstName")
     */
    private $firstName;

    /**
     * @ORM\Column(nullable=true)
     * @Serializer\SerializedName("lastName")
     */
    private $lastName;

    /**
     * @ORM\Column(length=512, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;
    /**
     * @ORM\ManyToMany(targetEntity="Song", mappedBy="artists")
     * @Serializer\Exclude()
     */
    private $songs;

    /**
     * @ORM\ManyToMany(targetEntity="Video", mappedBy="artists")
     * @Serializer\Exclude()
     */
    private $videos;

    /**
     * @ORM\ManyToMany(targetEntity="Audio", mappedBy="artists")
     * @Serializer\Exclude()
     */
    private $audio;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->audio = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Artist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Artist
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Artist
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Artist
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Artist
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add song
     *
     * @param \AppBundle\Entity\Song $song
     *
     * @return Artist
     */
    public function addSong(\AppBundle\Entity\Song $song)
    {
        $this->songs[] = $song;

        return $this;
    }

    /**
     * Remove song
     *
     * @param \AppBundle\Entity\Song $song
     */
    public function removeSong(\AppBundle\Entity\Song $song)
    {
        $this->songs->removeElement($song);
    }

    /**
     * Get songs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * Add video
     *
     * @param \AppBundle\Entity\Video $video
     *
     * @return Artist
     */
    public function addVideo(\AppBundle\Entity\Video $video)
    {
        $this->videos[] = $video;

        return $this;
    }

    /**
     * Remove video
     *
     * @param \AppBundle\Entity\Video $video
     */
    public function removeVideo(\AppBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add audio
     *
     * @param \AppBundle\Entity\Audio $audio
     *
     * @return Artist
     */
    public function addAudio(\AppBundle\Entity\Audio $audio)
    {
        $this->audio[] = $audio;

        return $this;
    }

    /**
     * Remove audio
     *
     * @param \AppBundle\Entity\Audio $audio
     */
    public function removeAudio(\AppBundle\Entity\Audio $audio)
    {
        $this->audio->removeElement($audio);
    }

    /**
     * Get audio
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAudio()
    {
        return $this->audio;
    }
}
