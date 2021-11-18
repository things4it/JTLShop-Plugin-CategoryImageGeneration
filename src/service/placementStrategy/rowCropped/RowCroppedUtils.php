<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;

use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;

class RowCroppedUtils
{

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

}