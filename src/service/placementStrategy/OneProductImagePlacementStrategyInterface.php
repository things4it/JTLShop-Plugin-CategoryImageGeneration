<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;

interface OneProductImagePlacementStrategyInterface extends ImagePlacementStrategy
{
    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage);
}