<?php


namespace App\Controller;


use App\DTO\Transformer\OverTimeResponseDtoTransformer;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class OverTimeController extends AbstractApiController
{
    protected $manager;
    private $overTimeResponseTransformer;

    public function __construct(
        EntityManagerInterface $manager,
        OverTimeResponseDtoTransformer $overTimeResponseTransformer
    ) {
        $this->manager = $manager;
        $this->overTimeResponseTransformer = $overTimeResponseTransformer;
    }


    public function show($hotel_id, $start, $end): Response
    {
        //TODO:: validate date range

        // get grouped reviews based on date range
        $reviews = $this->manager->getRepository(Review::class)->findByHotelId($hotel_id, $start, $end);

        // pass reviews to DTO
        $dto = $this->overTimeResponseTransformer->transformFromObjects($reviews);

        // Json encode
        $serialized = $this->serialize($dto);

        return new Response($serialized);
    }
}
