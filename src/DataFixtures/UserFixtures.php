<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setName('Fabio')
            ->setSurname('Ventura')
            ->setEmail('foravatenubi@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'fabio'));
        $manager->persist($user);

        $user = (new User())
            ->setName('Mario')
            ->setSurname('Rossi')
            ->setEmail('mario.rossi@mailinator.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'mario'));
        $manager->persist($user);
        $manager->flush();
    }
}
