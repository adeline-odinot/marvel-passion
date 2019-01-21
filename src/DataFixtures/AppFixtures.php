<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $users = [];

        for($i=1; $i <=10; $i++)
        {

            $user = new Users();

            $hash = $this->encoder->encodePassword($user, 'mdp');

            $user->setFirstName("Prénom n°$i")
                   ->setLastName("Nom n°$i")
                   ->setPseudo("Pseudo n°$i")
                   ->setHash($hash)
                   ->setEmail("blabla$i@gmail.com")
                   ->setAvatar("http://placehold.it/64x64")
                   ->setDescription("<p>Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. </p>");

            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();
    }
}
