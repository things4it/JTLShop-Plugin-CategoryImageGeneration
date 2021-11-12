<?php

namespace Plugin\t4it_category_image_generation\src\model;


class ImageRatioFactory
{
    public static function createFromRatioString(string $ratioString): ImageRatio
    {
        if ($ratioString == ImageRatio::RATIO_4_TO_3) {
            return new ImageRatio(ImageRatio::RATIO_4_TO_3, 1024, 768);
        }else if($ratioString == ImageRatio::RATIO_4_TO_2) {
            return new ImageRatio(ImageRatio::RATIO_4_TO_2, 1024, 512);
        }

        return new ImageRatio(ImageRatio::RATIO_1_TO_1, 1024, 1024);
    }

}