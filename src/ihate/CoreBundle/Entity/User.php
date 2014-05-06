<?php

namespace ihate\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"}), @ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="country", columns={"country_id"})})
 * @ORM\Entity(repositoryClass="ihate\CoreBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=false)
     */
    protected $surname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="email", type="string", length=45, nullable=false)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.")
     */
    protected $email;

    /**
     * @Assert\Image()
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", length=45, nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     *
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Your password must be at least {{ limit }} characters length",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters length")
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    protected $about;
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32, nullable=false)
     */
    protected $salt;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
     * })
     */
    protected $country;

    /**
     * @var hate
     *
     * @ORM\OneToMany(targetEntity="Hate", mappedBy="user")
     */
    protected $hates;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="following")
     * @ORM\JoinTable(name="follow",
     *      joinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="following_id", referencedColumnName="id")}
     * )
     */
    protected $follower;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="follower")
     */
    protected $following;

    public function __construct()
    {
        $this->initSalt();
        $this->follower = new ArrayCollection();
        $this->hates = new ArrayCollection();
    }

    public function isHated(Post $post)
    {
        foreach ($this->hates as $hate) {
            if ($hate->getPost() == $post) {
                return true;
            }
        }

        return false;
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

    public function __sleep()
    {
        return array('id');
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set country
     *
     * @param \ihate\CoreBundle\Entity\Country $country
     * @return User
     */
    public function setCountry(\ihate\CoreBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \ihate\CoreBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    public function initSalt() {
        $this->salt = substr(sha1(rand(1, 1000000)), 0, 32);
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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

    public function getRoles()
    {
       return array('ROLE_USER');
    }


    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function isFollowed(User $user)
    {
        foreach ($this->follower as $follow) {
            if ($follow == $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add follower
     *
     * @param \ihate\CoreBundle\Entity\User $follower
     * @return User
     */
    public function addFollow(User $follower)
    {
        $this->follower[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \ihate\CoreBundle\Entity\User $follower
     */
    public function removeFollow(User $follower)
    {
        $this->follower->removeElement($follower);
    }

    /**
     * Get follower
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowers()
    {
        return $this->follower;
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
        return 'uploads/avatar';
    }

    public function haveImage()
    {
        $path = $this->getWebPath();

        if (!$path) {
            return false;
        }
        return true;
    }

    public function showImage()
    {
        $path = $this->getWebPath();

        if (!$path) {
            $path = 'js/holder.js/150x150';
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

    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $fileName = $this->getId().'_avatar.'.$this->getFile()->guessExtension();

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $fileName
        );

        $this->path = $fileName;

        $this->file = null;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return User
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Add following
     *
     * @param \ihate\CoreBundle\Entity\User $following
     * @return User
     */
    public function addFollowing(\ihate\CoreBundle\Entity\User $following)
    {
        $this->following[] = $following;

        return $this;
    }

    /**
     * Remove following
     *
     * @param \ihate\CoreBundle\Entity\User $following
     */
    public function removeFollowing(\ihate\CoreBundle\Entity\User $following)
    {
        $this->following->removeElement($following);
    }

    /**
     * Get following
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
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
}
