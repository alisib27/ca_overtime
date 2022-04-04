<?php


namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
class AbstractApiController extends AbstractFOSRestController
{

    protected function respond($data, int $statusCode = Response::HTTP_OK): Response
    {

        $view = $this->view($data, $statusCode);

       return $this->handleView($view);
    }
    protected function serialize($data, int $statusCode = Response::HTTP_OK):string
    {
        $normalizers = [
            new ObjectNormalizer(),
        ];
        $encoders = [
            new JsonEncoder(),
        ];
        $serializer = new Serializer($normalizers,$encoders);

       return $serializer->serialize($data, 'json');

    }
}
