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
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

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
     * @ORM\ManyToMany(targetEntity="User", inversedBy="following")
     * @ORM\JoinTable(name="follow",
     *      joinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="following_id", referencedColumnName="id")}
     * )
     */
    protected $follows;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="follows")
     */
    protected $following;

    public function __construct()
    {
        $this->initSalt();
        $this->follows = new ArrayCollection();
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
        foreach ($this->follows as $follow) {
            if ($follow == $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add follows
     *
     * @param \ihate\CoreBundle\Entity\User $follows
     * @return User
     */
    public function addFollow(User $follows)
    {
        $this->follows[] = $follows;

        return $this;
    }

    /**
     * Remove follows
     *
     * @param \ihate\CoreBundle\Entity\User $follows
     */
    public function removeFollow(User $follows)
    {
        $this->follows->removeElement($follows);
    }

    /**
     * Get follows
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollows()
    {
        return $this->follows;
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

    public function showImage()
    {
        $path = $this->getWebPath();

        if (!$path) {
            $path = 'holder.js/150x150';
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

        $fileName = $this->getId().'_avatar.'.$this->getFile()->guessExtension();;

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
}
