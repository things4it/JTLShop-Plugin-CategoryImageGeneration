<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;

use Plugin\t4it_category_image_generation\src\model\ImageRatio;

class RowCroppedUtils
{
    /**
     * @param array $images
     */
    public static function sortImagesArrayByHeightAsc(array &$images)
    {
        usort($images, function($a, $b) {
            return imagesy($a) - imagesy($b);
        });
    }

    /**
     * @param $productImage
     * @param ImageRatio $imageRatio
     * @return int
     */
    public static function calculateOffsetYByRatio($productImage, ImageRatio $imageRatio): int
    {
        $imageHeight = imagesy($productImage);
        return ($imageRatio->getHeight() - $imageHeight) / 2;
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

        return (1024 - $widthOffAllImages - (RowCroppedConstants::PADDING * 2)) / 2;
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