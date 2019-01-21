<?php

namespace App\DataFixtures;

use App\Entity\Humor;
use App\Entity\Movies;
use App\Entity\Series;
use App\Entity\Role;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RolesFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);        

        $adminUser = new Users();
        $adminUser->setFirstName("Roselyne")
                  ->setLastName("Odinot")
                  ->setPseudo("Rosy")
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setEmail("rosy@gmail.com")
                  ->setAvatar("http://placehold.it/64x64")
                  ->setDescription("<p>Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. Je suis une description. </p>")
                  ->addUserRole($adminRole);
        
        $manager->persist($adminUser);

        for($i=1; $i <=10; $i++)
        {

            $series = new Series();
            $series->setTitle("Titre de la série n°$i")
                   ->setUser($adminUser)
                   ->setContent("<p>Contenu de la série n°$i</p>")
                   ->setImage("http://placehold.it/350x150")
                   ->setCreationDate(new \DateTime())
                   ->setIntroduction("<p>Introduction</p>");

            $manager->persist($series);
        }

        for($i=1; $i <=10; $i++)
        {
            $humor = new Humor();
            $humor->setTitle("Titre du meme d'humour n°$i")
                  ->setUser($adminUser)
                  ->setCreationDate(new \DateTime())
                  ->setImage("http://placehold.it/500x250");

            $manager->persist($humor);
        }

        for($i=1; $i <=10; $i++)
        {

            $movies = new Movies();
            $movies->setTitle("Titre du film n°$i")
                   ->setUser($adminUser)
                   ->setContent("<p>Contenu du film n°$i</p>")
                   ->setImage("http://placehold.it/350x150")
                   ->setCreationDate(new \DateTime())
                   ->setIntroduction("<p>Introduction</p>");

            $manager->persist($movies);
        }

        $manager->flush();
    }
}
