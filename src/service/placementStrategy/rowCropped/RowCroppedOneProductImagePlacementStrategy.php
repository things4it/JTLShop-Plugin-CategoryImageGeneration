<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowCroppedOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    private static int $WIDTH = 1024;
    private static int $HEIGHT = 512;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-cropped-one";
    }

    /**
     * @param $productImage
     */
    public function placeProductImages($productImage)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage = ImageUtils::resizeImageToMaxWidthHeight($productImage, 340, 340, 0);

        $productImageData = new RowCroppedImageData($productImage);

        // TODO: current impl is for 4:2 ratio add own strategies for other ratios
        $offsetY = RowCroppedUtils::calculateOffsetYByTargetImageHeight($productImageData, self::$HEIGHT);

        \imagecopyresized(
            $categoryImage,
            $productImageData->getImage(),
            342,
            $offsetY,
            0,
            0,
            340,
            340,
            $productImageData->getWidth(),
            $productImageData->getHeight()
        );

        return $categoryImage;
    }
}