<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;

class FlippedOffsetTwoProductImagesPlacementStrategy extends OffsetTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.flipped-offset");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-two";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2)
    {
        \imageflip($productImage1, IMG_FLIP_BOTH);
        \imageflip($productImage2, IMG_FLIP_BOTH);

        parent::placeProductImages($categoryImage, $imageRatio, $productImage1, $productImage2);
    }
}