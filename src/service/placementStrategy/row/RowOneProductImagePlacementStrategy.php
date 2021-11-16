<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    private static int $WIDTH = 1024;
    private static int $HEIGHT = 512;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-one";
    }

    public function placeProductImages($productImage)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage = ImageUtils::resizeImageToMaxWidthHeight($productImage, 340, 340, 1);

        $offsetY = 86;

        // TODO: current impl is for 4:2 ratio add own strategies for other ratios
//        $offsetY = RowUtils::calculateOffsetYByRatio($imageRatio);

        \imagecopyresized($categoryImage, $productImage, 342, $offsetY, 0, 0, 340, 340, imagesx($productImage), imagesy($productImage));

        return $categoryImage;
    }
}