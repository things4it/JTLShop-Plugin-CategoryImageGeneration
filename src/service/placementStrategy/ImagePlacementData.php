<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


class ImagePlacementData
{

    private $image;

    /**
     * @var int
     */
    private $width;


    /**
     * @var int
     */
    private $height;

    /**
     * ImagePlacementData constructor.
     * @param $image
     */
    public function __construct($image)
    {
        $this->image = $image;
        $this->width = imagesx($image);
        $this->height = imagesy($image);
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }



}