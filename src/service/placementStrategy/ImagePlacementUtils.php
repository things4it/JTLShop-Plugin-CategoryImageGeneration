<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy;

use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;

class ImagePlacementUtils
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
     * @param int $targetImageWidth
     * @param int $padding
     * @return int
     */
    public static function calculateOffsetXForImagesBlock(array $productImageDatas, int $targetImageWidth, int $paddingBetweenImages = 0): int
    {
        $widthOfAllImagesAndPaddingBetween = 0;
        foreach ($productImageDatas as $productImageData) {
            $widthOfAllImagesAndPaddingBetween += $productImageData->getWidth();
            $widthOfAllImagesAndPaddingBetween += $paddingBetweenImages;
        }

        // remove 1 padding because it is only padding "between"
        $widthOfAllImagesAndPaddingBetween -= $paddingBetweenImages;

        return ($targetImageWidth - $widthOfAllImagesAndPaddingBetween) / 2;
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