<?php

namespace App\Tests;

use App\Entity\Hotel;
use App\Entity\Review;

class OverTimeScoreTest extends DatabaseDependantTestCase
{
    /** @test */
    public function it_can_get_grouped_score(): void
    {
        //setup
        $this->setUp();


        $hotel = new Hotel();
        $hotel->setName("Sunny sunshine hotel");
        $this->entityManager->persist($hotel);
        $this->entityManager->flush();
        $h = $this->entityManager->getRepository(Hotel::class);

        $review = new Review();
        $review->setScore(5);
        $review->setComment("Something good");
        $review->setHotelId($hotel);
        $review->setCreatedAt(\DateTimeImmutable::createFromMutable(date_create("2022-11-03 15:51:34")));
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $review = new Review();
        $review->setScore(1);
        $review->setComment("Something bad");
        $review->setHotelId($hotel);
        $review->setCreatedAt(\DateTimeImmutable::createFromMutable(date_create("2022-11-03 11:51:34")));
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $review = new Review();
        $review->setScore(1);
        $review->setComment("Something breally bad");
        $review->setHotelId($hotel);
        $review->setCreatedAt(\DateTimeImmutable::createFromMutable(date_create("2022-11-04 13:51:34")));
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $review = new Review();
        $review->setScore(1);
        $review->setComment("Something breally bad");
        $review->setHotelId($hotel);
        $review->setCreatedAt(\DateTimeImmutable::createFromMutable(date_create("2022-12-04 13:51:34")));
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $start = '2022-11-01';
        $end = '2022-11-09';

        $reviews = $this->entityManager->getRepository(Review::class)->findByHotelId($hotel->getId(), $start, $end);

        $this->assertEquals(2, count($reviews));

        $this->assertEquals(2, $reviews[0]['review_count']);

        $this->assertEquals(3.000, $reviews[0]['average_score']);

        // weekly grouping case
        $start = '2022-11-01';
        $end = '2022-12-09';
        $reviews = $this->entityManager->getRepository(Review::class)->findByHotelId($hotel->getId(), $start, $end);
        //44th week of the year
        $this->assertEquals(44, $reviews[0]['date_range']);

        // monthly grouping case
        $start = '2022-11-01';
        $end = '2023-12-09';
        $reviews = $this->entityManager->getRepository(Review::class)->findByHotelId($hotel->getId(), $start, $end);

        $this->assertEquals(3, $reviews[0]['review_count']);
        //11th month of the year
        $this->assertEquals(11, $reviews[0]['date_range']);

        $this->tearDown();
    }

    /** @test */
    public function it_can_get_successful_response_from_api_endpoint(): void
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/overtime/25/2020-03-23/2020-05-23');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_will_throw_exception_for_wrong_dates(): void
    {
        $this->expectException(\GuzzleHttp\Exception\ServerException::class);
       $this->expectExceptionMessage('Wrong date format provided');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/overtime/25/abc/11');
        $this->assertEquals(500, $response->getStatusCode());
    }


}
