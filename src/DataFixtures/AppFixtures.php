<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

const CREATE_NUMBER   = 10;
const LOREM_IPSUM_URL = 'http://asdfast.beobit.net/api/';

class AppFixtures extends Fixture
{
    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->hasher   = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < CREATE_NUMBER; $i++) {
            $user     = new User();
            $password = $this->hasher->hashPassword($user, 'admin');

            $user->setName('user_' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 4));
            $user->setPassWord($password);
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
        }
        $manager->flush();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, LOREM_IPSUM_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $entityManager = $this->doctrine->getManager();
        $users         = $entityManager->getRepository(User::class)->findAll();
        $count         = 0;
        foreach ($users as $user) {
            $count++;
            $article  = new Article();
            $response = curl_exec($ch);

            $article->setUser($user);
            $article->setUserId($user->getId());
            $article->setTitle('article_' . $i + 1);
            $article->setText(json_decode($response)->text);
            $manager->persist($article);

            if ($count >= CREATE_NUMBER) {
                break;
            }

            sleep(1);
        }
        curl_close($ch);

        $manager->flush();
    }
}
