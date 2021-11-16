<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\offset;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class OffsetOneProductImagePlacementStrategy implements OneProductImagePlacementStrategyInterface
{
    private static int $WIDTH = 1024;
    private static int $HEIGHT = 768;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.offset");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-one";
    }

    public function placeProductImages($productImage)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage = ImageUtils::centerImageInSize($productImage);

        \imagecopyresized($categoryImage, $productImage, 162, 34, 0, 0, 700, 700, imagesx($productImage), imagesy($productImage));

        return $categoryImage;

        // TODO: current impl is for 4:3 ratio add own strategies for other ratios
//        if ($imageRatio->getCode() == ImageRatio::RATIO_1_TO_1) {
//            \imagecopyresized($categoryImage, $productImage, 0, 0, 0, 0, $imageRatio->getWidth(), $imageRatio->getHeight(), imagesx($productImage), imagesy($productImage));
//        } else if($imageRatio->getCode() == ImageRatio::RATIO_4_TO_3) {
//            \imagecopyresized($categoryImage, $productImage, 162, 34, 0, 0, 700, 700, imagesx($productImage), imagesy($productImage));
//        } else {
//            \imagecopyresized($categoryImage, $productImage, 262, 6, 0, 0, 500, 500, imagesx($productImage), imagesy($productImage));
//        }
    }
}