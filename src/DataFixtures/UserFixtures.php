<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER_FABIO_REFERENCE = 'user-fabio';
    public const USER_MARIO_REFERENCE = 'user-mario';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userFabio = (new User())
            ->setName('Fabio')
            ->setSurname('Ventura')
            ->setEmail('foravatenubi@gmail.com');
        $userFabio->setPassword($this->passwordEncoder->encodePassword($userFabio, 'fabio'));
        $manager->persist($userFabio);

        $userMario = (new User())
            ->setName('Mario')
            ->setSurname('Rossi')
            ->setEmail('mario.rossi@mailinator.com');
        $userMario->setPassword($this->passwordEncoder->encodePassword($userMario, 'mario'));
        $manager->persist($userMario);

        $manager->flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::USER_FABIO_REFERENCE, $userFabio);
        $this->addReference(self::USER_MARIO_REFERENCE, $userMario);
    }
}
