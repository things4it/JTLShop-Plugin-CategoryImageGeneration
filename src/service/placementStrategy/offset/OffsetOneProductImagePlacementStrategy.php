<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;

class OffsetOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage)
    {
        \imagecopyresized($categoryImage, $productImage, 0, 0, 0, 0, $imageRatio->getWidth(), $imageRatio->getHeight(), imagesx($productImage), imagesy($productImage));
    }
}