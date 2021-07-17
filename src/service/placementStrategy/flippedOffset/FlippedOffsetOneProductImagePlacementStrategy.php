<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;

class FlippedOffsetOneProductImagePlacementStrategy extends OffsetOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.flipped-offset");
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage)
    {
        \imageflip($productImage, IMG_FLIP_BOTH);
        parent::placeProductImages($categoryImage, $imageRatio, $productImage);
    }
}