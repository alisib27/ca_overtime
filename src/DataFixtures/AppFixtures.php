<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{

    public static $number_of_hotels = 10;
    public static $number_of_reviews = 10000;
    public static $min_score = 1;
    public static $max_score = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < self::$number_of_hotels; $i++) {
            $hotel = new Hotel();
            $hotel->setName($faker->company);
            $manager->persist($hotel);
        }
        $manager->flush();

        for ($i = 0; $i < self::$number_of_reviews; $i++) {
            $review = new Review();
            $review->setScore($faker->numberBetween($min = self::$min_score, $max = self::$max_score));
            $review->setComment($faker->realText());
            $hotels = $manager->getRepository(Hotel::class)->findAll();
            $review->setHotelId($faker->randomElement($hotels));
            $random_date = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null);
            $review->setCreatedAt(
                \DateTimeImmutable::createFromMutable($random_date)
            );
            $manager->persist($review);
        }
        $manager->flush();
    }
}
