<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class HorizontalOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.horizontal");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "horizontal-one";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage)
    {
        $productImage = ImageUtils::resizeImageToMaxWidthHeight($productImage, 340, 340, 1);

        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage, 342, 342, 0, 0, 340, 340, imagesx($productImage), imagesy($productImage));
        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
            \imagecopyresized($categoryImage, $productImage, 342, 214, 0, 0, 340, 340, imagesx($productImage), imagesy($productImage));
        } else {
            \imagecopyresized($categoryImage, $productImage, 342, 86, 0, 0, 340, 340, imagesx($productImage), imagesy($productImage));
        }

    }
}