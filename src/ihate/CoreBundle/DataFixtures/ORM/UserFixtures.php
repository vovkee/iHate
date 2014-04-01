<?php

namespace ihate\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ihate\CoreBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Country;


class LoadUserData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $countryRepo = $manager->getRepository('ihateCoreBundle:Country');
        $countries = $countryRepo->findAll();
        $genders = array('m', 'f');

        for($i = 0; $i <= 70; $i++)
        {
            $user = new User();
            $user->setName('Bob'.$i);
            $user->setSurname('Dope'.$i);
            $user->setEmail('user'.$i.'@ihate.lv');
            $user->setGender($genders[rand(0,1)]);
            $user->setCountry($countries[rand(0, count($countries) - 1)]);
            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user)
            ;
            $user->setPassword($encoder->encodePassword('123', $user->getSalt()));

            $manager->persist($user);
        }
        $manager->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}