<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class HorizontalThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.horizontal");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "horizontal-three";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2, $productImage3)
    {
        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 1);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 1);
        $productImage3 = ImageUtils::resizeImageToMaxWidthHeight($productImage3, 340, 340, 1);

        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage1, 0, 342, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 340 + 1, 342, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));
            \imagecopyresized($categoryImage, $productImage3, 340 + 1 + 340 + 1, 342, 0, 0, 340, 340, imagesx($productImage3), imagesy($productImage3));
        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
            \imagecopyresized($categoryImage, $productImage1, 0, 214, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 340 + 1, 214, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));
            \imagecopyresized($categoryImage, $productImage3, 340 + 1 + 340 + 1, 214, 0, 0, 340, 340, imagesx($productImage3), imagesy($productImage3));
        } else {
            \imagecopyresized($categoryImage, $productImage1, 0, 86, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 340 + 1, 86, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));
            \imagecopyresized($categoryImage, $productImage3, 340 + 1 + 340 + 1, 86, 0, 0, 340, 340, imagesx($productImage3), imagesy($productImage3));
        }
    }
}