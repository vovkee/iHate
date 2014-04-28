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
     * @param User $u1
     * @param User $u2
     */
    public function connect(User $u1, User $u2)
    {
        $u1->addFollow($u2);
        $this->entityManager->flush();
    }
    /**
     * @param User $u1
     * @param User $u2
     */
    public function followRemove(User $u1, User $u2)
    {
        $u1->removeFollow($u2);
        $this->entityManager->flush();
    }
    /**
     * @param User $u1
     * @param Hate $h2
     */
    public function hate(User $u1, Hate $h2)
    {
        $u1->addHate($h2);
        $this->entityManager->flush($u1);
    }
    /**
     * @param User $u1
     * @param Hate $h2
     */
    public function hateRemove(User $u1, Hate $h2)
    {
        $this->entityManager->remove($h2);
        $this->entityManager->flush();
    }
} 