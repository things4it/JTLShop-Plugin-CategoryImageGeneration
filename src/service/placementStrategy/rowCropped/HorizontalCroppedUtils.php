<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;

use Plugin\t4it_category_image_generation\src\model\ImageRatio;

class HorizontalCroppedUtils
{
    /**
     * @param ImageRatio $imageRatio
     * @return int
     */
    public static function calculateOffsetYByRatio(ImageRatio $imageRatio): int
    {
        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
            return 342;
        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
            return 214;
        } else {
            return 86;
        }
    }

    /**
     * @param array $productImages
     * @return int
     */
    public static function calculateOffsetXForImagesBlock(array $productImages): int
    {
        $widthOffAllImages = 0;
        foreach ($productImages as $productImage) {
            $widthOffAllImages += imagesx($productImage);
        }

        return (1024 - $widthOffAllImages - (HorizontalCroppedConstants::PADDING * 2)) / 2;
    }

    /**
     * @param $sourceImage
     * @param int $offsetX
     * @param int $offsetY
     * @param $targetImage
     */
    public static function copyImage($sourceImage, int $offsetX, int $offsetY, $targetImage)
    {
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);
        \imagecopyresized($targetImage, $sourceImage, $offsetX, $offsetY, 0, 0, $width, $height, $width, $height);
    }
}