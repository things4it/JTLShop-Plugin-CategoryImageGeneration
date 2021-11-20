<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class OffsetRatio1to1OneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    /**
     * @var int
     */
    private static $WIDTH = 1024;

    /**
     * @var int
     */
    private static $HEIGHT = 1024;

    // php 7.3.32

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset-ratio1to1");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-ratio1to1-one";
    }

    public function placeProductImages($productImage)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage = ImageUtils::centerImageInSize($productImage);

        \imagecopyresized($categoryImage, $productImage, 162, 34, 0, 0, 700, 700, imagesx($productImage), imagesy($productImage));

        return $categoryImage;
    }
}