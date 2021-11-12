<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class OffsetTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-two";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2)
    {
        $productImage1 = ImageUtils::centerImageInSize($productImage1);
        $productImage2 = ImageUtils::centerImageInSize($productImage2);

        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 500, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 500, 500, 0, 0, 500, 500, imagesx($productImage2), imagesy($productImage2));
        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
            \imagecopyresized($categoryImage, $productImage1, 130, 0, 0, 0, 375, 375, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 130 + 375 + 2, 375, 0, 0, 375, 375, imagesx($productImage2), imagesy($productImage2));
        } else {
            \imagecopyresized($categoryImage, $productImage1, 171, 40, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
            \imagecopyresized($categoryImage, $productImage2, 171 + 2 + 340, 132, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));
        }
    }
}