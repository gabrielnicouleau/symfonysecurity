<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Commentary;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}
    public function load(ObjectManager $manager): void
    {
    $faker = Faker\Factory::create('fr_FR');

        $users = [];
        $categories = [];
        $articles = [];

        //25 Utilisateurs
        for ($i=0; $i < 25; $i++) { 
            $user = new User();
            $user
                ->setFirstname($faker->firstName('male'|'female'))
                ->setLastname($faker->lastName())
                ->setEmail($user->getFirstname() . '.'. $user->getLastName() . '@' . $faker->freeEmailDomain())
                ->setPassword($this->hasher->hashPassword($user, '1234'))
                ->setRoles(['ROLE_USER'])
                ->setActived(false);
            $manager->persist($user);
            $users[] = $user;
        }

        //30 Categories
        for ($i=0 ; $i < 30; $i++) {
            $category = new Category();
            $category->setLibele($faker->jobTitle());
            $manager->persist($category);
            $categories[] = $category;
        }

        //100 articles
        for ($i=0; $i < 100 ; $i++) { 
            $article = new Article();
            $article
                ->setTitle("Article : " . $faker->word())
                ->setContent($faker->realText(400))
                ->setStatus(true)
                ->setCreateAt(new \DateTimeImmutable($faker->date() . $faker->time()))
                ->setAuthor($users[$faker->numberBetween(0, 24)])
                ->addCategory($categories[$faker->numberBetween(0, 9)])
                ->addCategory($categories[$faker->numberBetween(10, 19)])
                ->addCategory($categories[$faker->numberBetween(20, 29)]);
            $manager->persist($article);
            $articles[] = $article;
        }

        //10 commentaires / articles
        foreach ($articles as $art) {
            for ($i=0; $i < 10; $i++) { 
                $commentary = new Commentary();
                $commentary
                    ->setMessage($faker->realText(200))
                    ->setStatus(true)
                    ->setCreateAt(new \DateTimeImmutable($faker->date() . $faker->time()))
                    ->setAuthor($users[$faker->numberBetween(0, 24)])
                    ->setArticle($art);
                $manager->persist($commentary);
            }    
        }
        $manager->flush();
    }
}