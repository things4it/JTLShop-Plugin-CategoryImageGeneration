<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;

class FlipTwoProductImagesPlacementStrategy extends DefaultTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.flip");
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2)
    {
        \imageflip($productImage1, IMG_FLIP_BOTH);
        \imageflip($productImage2, IMG_FLIP_BOTH);

        parent::placeProductImages($categoryImage, $imageRatio, $productImage1, $productImage2);
    }
}