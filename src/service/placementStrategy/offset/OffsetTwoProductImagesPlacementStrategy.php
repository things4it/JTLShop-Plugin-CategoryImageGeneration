<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class OffsetTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    private static int $WIDTH = 1024;
    private static int $HEIGHT = 768;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-two";
    }

    public function placeProductImages($productImage1, $productImage2)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage1 = ImageUtils::centerImageInSize($productImage1);
        $productImage2 = ImageUtils::centerImageInSize($productImage2);

        \imagecopyresized($categoryImage, $productImage1, 130, 0, 0, 0, 375, 375, imagesx($productImage1), imagesy($productImage1));
        \imagecopyresized($categoryImage, $productImage2, 130 + 375 + 2, 375, 0, 0, 375, 375, imagesx($productImage2), imagesy($productImage2));

        return $categoryImage;

        // TODO: current impl is for 4:3 ratio add own strategies for other ratios
//        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
//            \imagecopyresized($categoryImage, $productImage1, 0, 0, 0, 0, 500, 500, imagesx($productImage1), imagesy($productImage1));
//            \imagecopyresized($categoryImage, $productImage2, 500, 500, 0, 0, 500, 500, imagesx($productImage2), imagesy($productImage2));
//        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
//            \imagecopyresized($categoryImage, $productImage1, 130, 0, 0, 0, 375, 375, imagesx($productImage1), imagesy($productImage1));
//            \imagecopyresized($categoryImage, $productImage2, 130 + 375 + 2, 375, 0, 0, 375, 375, imagesx($productImage2), imagesy($productImage2));
//        } else {
//            \imagecopyresized($categoryImage, $productImage1, 171, 40, 0, 0, 340, 340, imagesx($productImage1), imagesy($productImage1));
//            \imagecopyresized($categoryImage, $productImage2, 171 + 2 + 340, 132, 0, 0, 340, 340, imagesx($productImage2), imagesy($productImage2));
//        }
    }
}