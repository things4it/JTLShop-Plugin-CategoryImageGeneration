<?php

namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row;

use Plugin\t4it_category_image_generation\src\model\ImageRatio;

class RowUtils
{

    /**
     * TODO: check if we still need it without ratio-config
     *
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

}