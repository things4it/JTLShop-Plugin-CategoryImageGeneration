<?php

namespace Plugin\t4it_category_image_generation\src\model;


class ImageRatio
{
    public const RATIO_1_TO_1 = '1:1';
    public const RATIO_4_TO_3 = '4:3';

    /**
     * @var string
     */
    private $code;

    /**
     * @var int
     */
    private $width;


    /**
     * @var int
     */
    private $height;

    /**
     * ImageRatio constructor.
     * @param string $code
     * @param int $width
     * @param int $height
     */
    public function __construct(string $code, int $width, int $height)
    {
        $this->code = $code;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

}