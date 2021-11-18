<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementUtils;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowFlatThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
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
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-flat-three";
    }

    public function placeProductImages($productImage1, $productImage2, $productImage3)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 1);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 1);
        $productImage3 = ImageUtils::resizeImageToMaxWidthHeight($productImage3, 340, 340, 1);

        $productImage1Data = new ImagePlacementData($productImage1);
        $productImage2Data = new ImagePlacementData($productImage2);
        $productImage3Data = new ImagePlacementData($productImage3);

        $offset1Y = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImage1Data, self::$HEIGHT);
        $offset2Y = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImage2Data, self::$HEIGHT);
        $offset3Y = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImage3Data, self::$HEIGHT);

        \imagecopyresized($categoryImage, $productImage1, 0, $offset1Y, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
        \imagecopyresized($categoryImage, $productImage2, 340 + 1, $offset2Y, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));
        \imagecopyresized($categoryImage, $productImage3, 340 + 1 + 340 + 1, $offset3Y, 0, 0, 340, 340, imagesx($productImage3), imagesy($productImage3));

        return $categoryImage;
    }
}