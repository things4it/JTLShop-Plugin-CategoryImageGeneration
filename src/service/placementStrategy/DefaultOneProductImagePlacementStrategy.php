<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


class DefaultOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{

    public function placeProductImages($categoryImage, $productImage)
    {
        \imagecopyresized($categoryImage, $productImage, 0, 0, 0, 0, 1024, 1024, imagesx($productImage), imagesy($productImage));
    }
}