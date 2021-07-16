<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;


use Plugin\t4it_category_image_generation\src\model\ImageRatio;

class FlipOneProductImagePlacementStrategy extends DefaultOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.flip");
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage)
    {
        \imageflip($productImage, IMG_FLIP_BOTH);
        parent::placeProductImages($categoryImage, $imageRatio, $productImage);
    }
}