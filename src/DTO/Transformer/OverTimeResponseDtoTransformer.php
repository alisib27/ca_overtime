<?php


namespace App\DTO\Transformer;

use App\DTO\Response\OverTimeResponseDto;

class OverTimeResponseDtoTransformer extends AbstractResponseDtoTransformer
{

    public function transformFromObject($object)
    {
        $dto = new OverTimeResponseDto();
        $dto->review_count = $object['review_count'];
        $dto->average_score = $object['average_score'];
        $dto->date_group = $object['date_range'];
        return $dto;
    }
}
