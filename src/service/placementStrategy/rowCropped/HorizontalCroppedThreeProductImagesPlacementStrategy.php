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
        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 1);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 1);
        $productImage3 = ImageUtils::resizeImageToMaxWidthHeight($productImage3, 340, 340, 1);

        $productImage1 = \imagecropauto($productImage1, \IMG_CROP_SIDES);
        $productImage2 = \imagecropauto($productImage2, \IMG_CROP_SIDES);
        $productImage3 = \imagecropauto($productImage3, \IMG_CROP_SIDES);

        $productImage1Width = imagesx($productImage1);
        $productImage1Height = imagesy($productImage1);

        $productImage2Width = imagesx($productImage2);
        $productImage2Height = imagesy($productImage2);

        $productImage3Width = imagesx($productImage3);
        $productImage3Height = imagesy($productImage3);

        $contentWidth = $productImage1Width + $productImage2Width + $productImage3Width;
        $contentWidthPadding = (1024 - $contentWidth) / 2;

        $productImage1Padding = $contentWidthPadding;
        $productImage2Padding = $contentWidthPadding + $productImage1Width;
        $productImage3Padding = $contentWidthPadding + $productImage1Width + $productImage2Width;

        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            \imagecopyresized($categoryImage, $productImage1, $productImage1Padding, 342, 0, 0, $productImage1Width, $productImage1Height, $productImage1Width, $productImage1Height);
            \imagecopyresized($categoryImage, $productImage2, $productImage2Padding, 342, 0, 0, $productImage2Width, $productImage2Height, $productImage2Width, $productImage2Height);
            \imagecopyresized($categoryImage, $productImage3, $productImage3Padding, 342, 0, 0, $productImage3Width, $productImage3Height, $productImage3Width, $productImage3Height);
        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
            \imagecopyresized($categoryImage, $productImage1, $productImage1Padding, 214, 0, 0, $productImage1Width, $productImage1Height, $productImage1Width, $productImage1Height);
            \imagecopyresized($categoryImage, $productImage2, $productImage2Padding, 214, 0, 0, $productImage2Width, $productImage2Height, $productImage2Width, $productImage2Height);
            \imagecopyresized($categoryImage, $productImage3, $productImage3Padding, 214, 0, 0, $productImage3Width, $productImage3Height, $productImage3Width, $productImage3Height);
        } else {
            \imagecopyresized($categoryImage, $productImage1, $productImage1Padding, 86, 0, 0, $productImage1Width, $productImage1Height, $productImage1Width, $productImage1Height);
            \imagecopyresized($categoryImage, $productImage2, $productImage2Padding, 86, 0, 0, $productImage2Width, $productImage2Height, $productImage2Width, $productImage2Height);
            \imagecopyresized($categoryImage, $productImage3, $productImage3Padding, 86, 0, 0, $productImage3Width, $productImage3Height, $productImage3Width, $productImage3Height);
        }
    }
}