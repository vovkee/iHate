<?php

namespace ihate\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Post
 *
 * @ORM\Table(name="post", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})} )
 * @ORM\Entity(repositoryClass="ihate\CoreBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Assert\File(maxSize="5M")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var string
     *
     * @ORM\Column(name="embed", type="string", length=255, nullable=true)
     */
    private $embed;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", length=45, nullable=false)
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var hates
     * @ORM\OneToMany(targetEntity="Hate", mappedBy="post")
     */
    private $hates;


    public function __construct()
    {
        $this->hates = new ArrayCollection();
    }
    /**
     * Add Hate
     *
     * @param \ihate\CoreBundle\Entity\Hate $hate
     * @return hate
     */
    public function addHate(Hate $hate)
    {
        $this->hates->add($hate);

        return $this;
    }


    /**
     * Remove Hate
     *
     * @param \ihate\CoreBundle\Entity\Hate $hate
     */
    public function removeHate(Hate $hate)
    {
        $this->hates->removeElement($hate);
    }
    /**
     * Get hates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHates()
    {
        return $this->hates;
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

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/post';
    }

    public function showImage()
    {
        $path = $this->getWebPath();

        if (!$path) {
            $path = 'zaglushka.png';
        }

        return $path;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set embed
     *
     * @param string $embed
     * @return Post
     */
    public function setEmbed($embed)
    {
        if (preg_match('![?&]{1}v=([^&]+)!', $embed. '&', $m)){
            $video_id = $m[1];
            $this->embed = $video_id;
            return $this;
        }

        return $this;
    }

    /**
     * Get embed
     *
     * @return string 
     */
    public function getEmbed()
    {
        return $this->embed;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \ihate\CoreBundle\Entity\User $user
     * @return Post
     */
    public function setUser(\ihate\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ihate\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $fileName = $this->getId().'_post.'.$this->getFile()->getExtension();

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $fileName
        );

        $this->path = $fileName;

        $this->file = null;
    }

    public function getHatesByCountry($country)
    {
        $hates = array();
        foreach ($this->getHates() as $hate) {
            if ($hate->getUser()->getCountry() == $country) {
                $hates[] = $hate;
            }
        }
        return $hates;
    }
}
