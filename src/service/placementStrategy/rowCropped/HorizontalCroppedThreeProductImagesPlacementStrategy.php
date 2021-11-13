<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class HorizontalCroppedThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.horizontal-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "horizontal-cropped-three";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2, $productImage3)
    {
        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 0);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 0);
        $productImage3 = ImageUtils::resizeImageToMaxWidthHeight($productImage3, 340, 340, 0);

        $productImage1 = \imagecropauto($productImage1, \IMG_CROP_SIDES);
        $productImage2 = \imagecropauto($productImage2, \IMG_CROP_SIDES);
        $productImage3 = \imagecropauto($productImage3, \IMG_CROP_SIDES);

        $productImages = array($productImage1, $productImage2, $productImage3);

        $offsetY = HorizontalCroppedUtils::calculateOffsetYByRatio($imageRatio);
        $offsetX = HorizontalCroppedUtils::calculateOffsetXForImagesBlock($productImages);
        foreach ($productImages as $productImage){
            HorizontalCroppedUtils::copyImage($productImage, $offsetX, $offsetY, $categoryImage);
            $offsetX += imagesx($productImage) + HorizontalCroppedConstants::PADDING;
        }
    }

}