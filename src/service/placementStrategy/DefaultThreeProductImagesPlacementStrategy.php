<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


class DefaultThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{

    public function placeProductImages($categoryImage, $productImage1, $productImage2, $productImage3)
    {
        \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 500, imagesx($productImage1), imagesy($productImage1));
        \imagecopyresized($categoryImage, $productImage2, 500, 24, 0, 0, 500, 500, imagesx($productImage2), imagesy($productImage2));
        \imagecopyresized($categoryImage, $productImage3, 250, 500 + 24, 0, 0, 500, 500, imagesx($productImage3), imagesy($productImage3));
    }
}