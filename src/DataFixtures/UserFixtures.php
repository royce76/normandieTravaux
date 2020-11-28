<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const USER_REFERENCE = 'user_fixture';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("toto@toto.com");
        $user->setRoles($user->getRoles());
        $user->setPassword($this->passwordEncoder->encodePassword($user,'Lola2013!'));
        $user->setIsVerified(TRUE);
        $user->setFirstname('royce');
        $user->setLastname('george');
        $this->addReference(self::USER_REFERENCE, $user);

        $manager->persist($user);
        $manager->flush();
    }
}
