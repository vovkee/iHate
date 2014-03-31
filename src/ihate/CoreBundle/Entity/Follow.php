<?php

namespace ihate\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Follow
 *
 * @ORM\Table(name="follow")
 * @ORM\Entity(repositoryClass="ihate\CoreBundle\Repository\FollowRepository")
 */
class Follow
{
    /**
     * @var integer
     *
     * @ORM\Column(name="follower_id", type="integer", nullable=false)
     */
    private $followerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="following_id", type="integer", nullable=false)
     */
    private $followingId;

    /**
     * Set followerId
     *
     * @param integer $followerId
     * @return Follow
     */
    public function setFollowerId($followerId)
    {
        $this->followerId = $followerId;

        return $this;
    }

    /**
     * Get followerId
     *
     * @return integer 
     */
    public function getFollowerId()
    {
        return $this->followerId;
    }

    /**
     * Set followingId
     *
     * @param integer $followingId
     * @return Follow
     */
    public function setFollowingId($followingId)
    {
        $this->followingId = $followingId;

        return $this;
    }

    /**
     * Get followingId
     *
     * @return integer 
     */
    public function getFollowingId()
    {
        return $this->followingId;
    }
}
