<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;

class RowCroppedUtils
{
    /**
     * @param RowCroppedImageData[] $images
     */
    public static function sortImagesArrayByHeightAsc(array &$images)
    {
        usort($images, function(RowCroppedImageData $a, RowCroppedImageData $b) {
            return $a->getHeight() - $b->getHeight();
        });
    }

    /**
     * @param RowCroppedImageData $productImageData
     * @param int $height
     * @return int
     */
    public static function calculateOffsetYByTargetImageHeight(RowCroppedImageData $productImageData, int $height): int
    {
        return ($height - $productImageData->getHeight()) / 2;
    }

    /**
     * @param RowCroppedImageData[] $productImageDatas
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
     * @param RowCroppedImageData $sourceImageData
     * @param int $offsetX
     * @param int $offsetY
     * @param $targetImage
     */
    public static function copyImage(RowCroppedImageData $sourceImageData, int $offsetX, int $offsetY, $targetImage)
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