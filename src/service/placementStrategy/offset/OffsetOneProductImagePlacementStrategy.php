<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class OffsetOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-one";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage)
    {
        $productImage = ImageUtils::centerImageInSize($productImage);

        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage, 0, 0, 0, 0, $imageRatio->getWidth(), $imageRatio->getHeight(), imagesx($productImage), imagesy($productImage));
        } else {
            \imagecopyresized($categoryImage, $productImage, 162, 34, 0, 0, 700, 700, imagesx($productImage), imagesy($productImage));
        }
    }
}