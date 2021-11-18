<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;

use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;

class RowCroppedUtils
{
    /**
     * @param ImagePlacementData[] $images
     */
    public static function sortImagesArrayByHeightAsc(array &$images)
    {
        usort($images, function(ImagePlacementData $a, ImagePlacementData $b) {
            return $a->getHeight() - $b->getHeight();
        });
    }

    /**
     * @param ImagePlacementData $productImageData
     * @param int $height
     * @return int
     */
    public static function calculateOffsetYByTargetImageHeight(ImagePlacementData $productImageData, int $height): int
    {
        return ($height - $productImageData->getHeight()) / 2;
    }

    /**
     * @param ImagePlacementData[] $productImageDatas
     * @return int
     */
    public static function calculateOffsetXForImagesBlock(array $productImageDatas): int
    {
        $widthOffAllImages = 0;
        foreach ($productImageDatas as $productImageData) {
            $widthOffAllImages += $productImageData->getWidth();
        }

        return (1024 - $widthOffAllImages - (RowCroppedConstants::PADDING * 2)) / 2;
    }

    /**
     * @param ImagePlacementData $sourceImageData
     * @param int $offsetX
     * @param int $offsetY
     * @param $targetImage
     */
    public static function copyImage(ImagePlacementData $sourceImageData, int $offsetX, int $offsetY, $targetImage)
    {
        \imagecopyresized(
            $targetImage,
            $sourceImageData->getImage(),
            $offsetX,
            $offsetY,
            0,
            0,
            $sourceImageData->getWidth(),
            $sourceImageData->getHeight(),
            $sourceImageData->getWidth(),
            $sourceImageData->getHeight()
        );
    }
}