<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Status;
use App\Entity\UserBook;
use App\Entity\Publisher;
use Faker\Factory as Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        // Création of 10 Authors
        $authors = [];
        for ($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author->setName($faker->name);
            $manager->persist($author);
            $authors[] = $author;
        }

        // Create of 10 Publishers
        $publishers = [];
        for ($i = 0; $i < 10; $i++) {
            $publisher = new Publisher();
            $publisher->setName($faker->company);
            $manager->persist($publisher);
            $publishers[] = $publisher;
        }

        // Create Status
        $status = [];
        foreach (['A lire', 'En train de le lire', 'Lu'] as $value) {
            $oneStatus = new Status();
            $oneStatus->setName($value);
            $manager->persist($oneStatus);
            $status[] = $oneStatus;
        }

        // Create 100 Books
        $books = [];
        for ($i = 0; $i < 100; $i++) {
            $book = new Book();
            $book
                ->setGoogleBooksId($faker->uuid)
                ->setTitle($faker->sentence)
                ->setSubtitle($faker->sentence)
                ->setPublishDate($faker->dateTime)
                ->setDescription($faker->text)
                ->setIsbn10($faker->isbn10)
                ->setIsbn13($faker->isbn13)
                ->setPageCount($faker->numberBetween(100, 1000))
                ->setThumbnail('https://picsum.photos/200/300')
                ->setSmallThumbnail('https://picsum.photos/100/150')
                ->addAuthor($faker->randomElement($authors))
                ->addPublisher($faker->randomElement($publishers));

            $manager->persist($book);
            $books[] = $book;
        }

        // Create 10 Users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email)
                ->setPassword($faker->password)
                ->setPseudo($faker->userName);

            $manager->persist($user);
            $users[] = $user;
        }

        // Create 10 UserBook by User
        foreach ($users as $user) {
            for ($i = 0; $i < 10; $i++) {
                $userBook = new UserBook();
                $userBook
                    ->setReader($user)
                    ->setStatus($faker->randomElement($status))
                    ->setBook($faker->randomElement($books))
                    ->setRating($faker->numberBetween(0, 5))
                    ->setComment($faker->text)
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime))
                    ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime));

                $manager->persist($userBook);
            }
        }

        $manager->flush();
    }
}
