<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowFlatOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    /**
     * @var int
     */
    private static $WIDTH = 1024;

    /**
     * @var int
     */
    private static $HEIGHT = 342;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-flat");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-flat-one";
    }

    public function placeProductImages($productImage)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage = ImageUtils::resizeImageToMaxWidthHeight($productImage, 340, 340, 1);

        \imagecopyresized($categoryImage, $productImage, 342, 1, 0, 0, 340, 340, imagesx($productImage), imagesy($productImage));

        return $categoryImage;
    }
}