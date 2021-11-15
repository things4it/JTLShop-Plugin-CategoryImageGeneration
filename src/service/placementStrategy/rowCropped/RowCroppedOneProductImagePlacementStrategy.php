<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowCroppedOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-cropped-one";
    }

    /**
     * @param $categoryImage
     * @param ImageRatio $imageRatio
     * @param $productImage
     */
    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage)
    {
        $productImage = ImageUtils::resizeImageToMaxWidthHeight($productImage, 340, 340, 0);

        $offsetY = RowCroppedUtils::calculateOffsetYByRatio($productImage, $imageRatio);
        \imagecopyresized($categoryImage, $productImage, 342, $offsetY, 0, 0, 340, 340, imagesx($productImage), imagesy($productImage));
    }
}