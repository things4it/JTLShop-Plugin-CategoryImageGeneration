<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementUtils;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowFlatThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    /**
     * @var int
     */
    private static $WIDTH = 1024;

    /**
     * @var int
     */
    private static $HEIGHT = 342;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-flat");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-flat-three";
    }

    public function placeProductImages($productImage1, $productImage2, $productImage3)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 1);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 1);
        $productImage3 = ImageUtils::resizeImageToMaxWidthHeight($productImage3, 340, 340, 1);

        $productImage1Data = new ImagePlacementData($productImage1);
        $productImage2Data = new ImagePlacementData($productImage2);
        $productImage3Data = new ImagePlacementData($productImage3);

        $productImageDatas = $this->createProductImageArraySortedByHeight($productImage1Data, $productImage2Data, $productImage3Data);

        $offsetX = ImagePlacementUtils::calculateOffsetXForImagesBlock($productImageDatas, self::$WIDTH);
        foreach ($productImageDatas as $productImageData){
            $offsetY = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImageData, self::$HEIGHT);
            ImagePlacementUtils::copyImage($productImageData, $offsetX, $offsetY, $categoryImage);
            $offsetX += $productImageData->getWidth() + 1;
        }

        return $categoryImage;
    }

    /**
     * @param ImagePlacementData[] $productImageDatas
     * @return ImagePlacementData[]
     */
    private function createProductImageArraySortedByHeight(... $productImageDatas): array
    {
        ImagePlacementUtils::sortImagesArrayByHeightAsc($productImageDatas);

        return array($productImageDatas[0], $productImageDatas[2], $productImageDatas[1]);
    }
}