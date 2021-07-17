<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;

class FlippedOffsetThreeProductImagesPlacementStrategy extends OffsetThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.flipped-offset");
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2, $productImage3)
    {
        \imageflip($productImage1, IMG_FLIP_BOTH);
        \imageflip($productImage2, IMG_FLIP_BOTH);
        \imageflip($productImage3, IMG_FLIP_BOTH);

        parent::placeProductImages($categoryImage, $imageRatio, $productImage1, $productImage2, $productImage3);
    }
}