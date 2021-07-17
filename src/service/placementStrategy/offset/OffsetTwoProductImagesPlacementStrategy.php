<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;

class OffsetTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2)
    {
        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 500, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 500, 500, 0, 0, 500, 500, imagesx($productImage2), imagesy($productImage2));
        } else {
            \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 375, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 500, 375, 0, 0, 500, 375, imagesx($productImage2), imagesy($productImage2));
        }
    }
}