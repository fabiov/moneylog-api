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
            ->getEmail('foravatenubi@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'myPassword'));

        $manager->persist($user);
        $manager->flush();
    }
}
