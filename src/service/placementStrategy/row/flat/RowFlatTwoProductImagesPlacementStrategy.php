<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementUtils;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowFlatTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
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
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-flat-two";
    }

    public function placeProductImages($productImage1, $productImage2)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 1);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 1);

        $productImage1Data = new ImagePlacementData($productImage1);
        $productImage2Data = new ImagePlacementData($productImage2);

        $offset1Y = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImage1Data, self::$HEIGHT);
        $offset2Y = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImage2Data, self::$HEIGHT);

        \imagecopyresized($categoryImage, $productImage1, 171, $offset1Y, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
        \imagecopyresized($categoryImage, $productImage2, 171 + 340 + $offset2Y, 1, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));

        return $categoryImage;
    }
}