<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class OffsetRatio1to1ThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    private static int $WIDTH = 1024;
    private static int $HEIGHT = 1024;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset-ratio1to1");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-ratio1to1-three";
    }

    public function placeProductImages($productImage1, $productImage2, $productImage3)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage1 = ImageUtils::centerImageInSize($productImage1);
        $productImage2 = ImageUtils::centerImageInSize($productImage2);
        $productImage3 = ImageUtils::centerImageInSize($productImage3);

        \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 500, imagesx($productImage1), imagesy($productImage1));
        \imagecopyresized($categoryImage, $productImage2, 500, 24, 0, 0, 500, 500, imagesx($productImage2), imagesy($productImage2));
        \imagecopyresized($categoryImage, $productImage3, 250, 500 + 24, 0, 0, 500, 500, imagesx($productImage3), imagesy($productImage3));

        return $categoryImage;
    }
}