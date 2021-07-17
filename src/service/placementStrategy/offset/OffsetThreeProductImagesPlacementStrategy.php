<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;

class OffsetThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-three";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2, $productImage3)
    {
        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 500, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 500, 24, 0, 0, 500, 500, imagesx($productImage2), imagesy($productImage2));
            \imagecopyresized($categoryImage, $productImage3, 250, 500 + 24, 0, 0, 500, 500, imagesx($productImage3), imagesy($productImage3));
        } else {
            \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 375, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 500, 24, 0, 0, 500, 375, imagesx($productImage2), imagesy($productImage2));
            \imagecopyresized($categoryImage, $productImage3, 250, 375 + 24, 0, 0, 500, 375, imagesx($productImage3), imagesy($productImage3));
        }
    }
}