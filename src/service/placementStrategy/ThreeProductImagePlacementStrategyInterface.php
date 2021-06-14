<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;

interface ThreeProductImagePlacementStrategyInterface
{
    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2, $productImage3);
}