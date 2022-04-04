<?php


namespace App\DTO\Transformer;


interface ResponseDtoTransformerInterface
{
    public function transformFromObject($object);
    public function transformFromObjects(iterable $objects): iterable;
}
