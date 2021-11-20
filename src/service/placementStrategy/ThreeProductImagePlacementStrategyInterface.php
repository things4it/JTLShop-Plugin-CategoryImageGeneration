<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


interface ThreeProductImagePlacementStrategyInterface extends ImagePlacementStrategy
{
    public function placeProductImages($productImage1, $productImage2, $productImage3);
}