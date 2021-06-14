<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


interface ThreeProductImagePlacementStrategyInterface
{
    public function placeProductImages($categoryImage, $productImage1, $productImage2, $productImage3);
}