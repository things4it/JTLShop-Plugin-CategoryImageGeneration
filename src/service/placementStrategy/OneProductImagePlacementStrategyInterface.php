<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


interface OneProductImagePlacementStrategyInterface
{
    public function placeProductImages($categoryImage, $productImage);
}