<?php

namespace ihate\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ihate\CoreBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Tom');
        $user->setSurname('Corki');
        $user->setEmail('user1@user.lv');
        $user->setGender('f');
        $user->setPassword('test1');

        $manager->persist($user);

        $user = new User();
        $user->setName('Mike');
        $user->setSurname('Well');
        $user->setEmail('user2@user.lv');
        $user->setGender('m');
        $user->setPassword('test2');

        $manager->persist($user);

        $user = new User();
        $user->setName('Bob');
        $user->setSurname('Red');
        $user->setEmail('user3@user.lv');
        $user->setGender('f');
        $user->setPassword('test3');

        $manager->persist($user);

        $user = new User();
        $user->setName('Rob');
        $user->setSurname('Conk');
        $user->setEmail('user4@user.lv');
        $user->setGender('m');
        $user->setPassword('test4');

        $manager->persist($user);

        $user = new User();
        $user->setName('Kep');
        $user->setSurname('REP');
        $user->setEmail('user5@user.lv');
        $user->setGender('m');
        $user->setPassword('test5');

        $manager->persist($user);

        $user = new User();
        $user->setName('Zed');
        $user->setSurname('More');
        $user->setEmail('user6@user.lv');
        $user->setGender('m');
        $user->setPassword('test6');



        for($i = 7; $i <= 60; $i++)
        {
            $user = new User();
            $user->setName('Zed'.$i);
            $user->setSurname('More'.$i);
            $user->setEmail('user'.$i.'@user.lv');
            $user->setGender('m');
            $user->setPassword('test'.$i);
            $manager->persist($user);
        }
        $manager->flush();
    }
}