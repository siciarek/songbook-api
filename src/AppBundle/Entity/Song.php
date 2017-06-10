<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * AppBundle\Entity\Song
 *
 * @ORM\Entity
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="SongRepository")
 */
class Song
{

    use ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\SoftDeletable\SoftDeletable;

    public function getAudioCount() {

        return $this->getAudio() === null ? 0 : $this->getAudio()->count();
    }

    public function getVideoCount() {
        return $this->getVideos() === null ? 0 : $this->getVideos()->count();
    }

    public function __toString()
    {
        $string = '?';

        $parts = array_filter([
            $this->getTitle(),
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
     * @ORM\OneToMany(targetEntity="Audio", mappedBy="song")
     */
    private $audio;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="song")
     */
    private $videos;

    /**
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="songs")
     */
    private $genre;

    /**
     * @ORM\ManyToMany(targetEntity="Artist", inversedBy="songs")
     */
    private $artists;

    /**
     * @ORM\ManyToMany(targetEntity="Author", inversedBy="songs")
     */
    private $authors;

    /**
     * @ORM\Column()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $lyrics;

    /**
     * @ORM\Column(length=512, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $firstPublishedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->audio = new \Doctrine\Common\Collections\ArrayCollection();
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->artists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Song
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set lyrics
     *
     * @param string $lyrics
     *
     * @return Song
     */
    public function setLyrics($lyrics)
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    /**
     * Get lyrics
     *
     * @return string
     */
    public function getLyrics()
    {
        return $this->lyrics;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Song
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
     * @return Song
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
     * Set firstPublishedAt
     *
     * @param \DateTime $firstPublishedAt
     *
     * @return Song
     */
    public function setFirstPublishedAt($firstPublishedAt)
    {
        $this->firstPublishedAt = $firstPublishedAt;

        return $this;
    }

    /**
     * Get firstPublishedAt
     *
     * @return \DateTime
     */
    public function getFirstPublishedAt()
    {
        return $this->firstPublishedAt;
    }

    /**
     * Add audio
     *
     * @param \AppBundle\Entity\Audio $audio
     *
     * @return Song
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

    /**
     * Add video
     *
     * @param \AppBundle\Entity\Video $video
     *
     * @return Song
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
     * Set genre
     *
     * @param \AppBundle\Entity\Genre $genre
     *
     * @return Song
     */
    public function setGenre(\AppBundle\Entity\Genre $genre = null)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return \AppBundle\Entity\Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Add artist
     *
     * @param \AppBundle\Entity\Artist $artist
     *
     * @return Song
     */
    public function addArtist(\AppBundle\Entity\Artist $artist)
    {
        $this->artists[] = $artist;

        return $this;
    }

    /**
     * Remove artist
     *
     * @param \AppBundle\Entity\Artist $artist
     */
    public function removeArtist(\AppBundle\Entity\Artist $artist)
    {
        $this->artists->removeElement($artist);
    }

    /**
     * Get artists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * Add author
     *
     * @param \AppBundle\Entity\Author $author
     *
     * @return Song
     */
    public function addAuthor(\AppBundle\Entity\Author $author)
    {
        $this->authors[] = $author;

        return $this;
    }

    /**
     * Remove author
     *
     * @param \AppBundle\Entity\Author $author
     */
    public function removeAuthor(\AppBundle\Entity\Author $author)
    {
        $this->authors->removeElement($author);
    }

    /**
     * Get authors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthors()
    {
        return $this->authors;
    }
}
