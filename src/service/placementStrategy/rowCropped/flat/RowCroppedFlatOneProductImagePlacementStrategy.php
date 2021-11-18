<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\flat;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\RowCroppedImageData;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\RowCroppedUtils;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowCroppedFlatOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    /**
     * @var int
     */
    private static $WIDTH = 1024;

    /**
     * @var int
     */
    private static $HEIGHT = 340;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-cropped-flat");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-cropped-flat-one";
    }

    /**
     * @param $productImage
     */
    public function placeProductImages($productImage)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage = ImageUtils::resizeImageToMaxWidthHeight($productImage, 340, 340, 0);

        $productImageData = new RowCroppedImageData($productImage);

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