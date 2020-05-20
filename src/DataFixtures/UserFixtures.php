<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const MARIO_REFERENCE    = 'user_mario';
    public const GIUSEPPE_REFERENCE = 'user_giuseppe';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userMario = (new User())
            ->setEmail('mario.rossi@fixture.it')
            ->setName('Mario')
            ->setSurname('Rossi');
        $userMario->setPassword($this->passwordEncoder->encodePassword($userMario, 'mario123'));
        $manager->persist($userMario);

        $userGiuseppe = (new User())
            ->setEmail('giuseppe.verdi@fixture.it')
            ->setPassword('giuseppe')
            ->setName('Giuseppe')
            ->setSurname('Verdi');
        $userGiuseppe->setPassword($this->passwordEncoder->encodePassword($userGiuseppe, 'giuseppe'));
        $manager->persist($userGiuseppe);

        $userFabio = (new User())
            ->setEmail('fabio.ventura@fixture.it')
            ->setName('Fabio')
            ->setSurname('Ventura');
        $userFabio->setPassword($this->passwordEncoder->encodePassword($userFabio, 'Fabio123'));
        $manager->persist($userFabio);

        $manager->flush();
        $this->addReference(self::MARIO_REFERENCE, $userMario);
        $this->addReference(self::GIUSEPPE_REFERENCE, $userGiuseppe);
    }
}
