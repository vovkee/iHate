<?php

namespace ihate\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;
use ihate\CoreBundle\Entity\Hate;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("ihate.manager.user")
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param User $user1
     * @param User $user2
     */
    public function follow(User $user1, User $user2)
    {
        $user1->addFollow($user2);
        $this->entityManager->flush();
    }
    /**
     * @param User $user1
     * @param User $user2
     */
    public function followRemove(User $user1, User $user2)
    {
        $user1->removeFollow($user2);
        $this->entityManager->flush();
    }
    /**
     * @param User $user1
     * @param Hate $hate
     */
    public function hate(User $user1, Hate $hate)
    {
        $user1->addHate($hate);
        $this->entityManager->flush($user1);
    }
    /**
     * @param User $user1
     * @param Hate $hate
     */
    public function hateRemove(User $user1, Hate $hate)
    {
        $this->entityManager->remove($hate);
        $this->entityManager->flush();
    }
} 