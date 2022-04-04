<?php


namespace App\DTO\Response;

class OverTimeResponseDto
{
    /**
     * @Serialization\Type("int")
     */
    public  $review_count;

    /**
     * @Serialization\Type("int")
     */

    public  $average_score;

    /**
     * @Serialization\Type("string")
     */
    public $date_group;
}
