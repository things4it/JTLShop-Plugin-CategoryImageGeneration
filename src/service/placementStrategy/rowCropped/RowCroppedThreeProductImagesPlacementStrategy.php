<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowCroppedThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    private static int $WIDTH = 1024;
    private static int $HEIGHT = 512;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-cropped-three";
    }

    /**
     * @param $productImage1
     * @param $productImage2
     * @param $productImage3
     */
    public function placeProductImages($productImage1, $productImage2, $productImage3)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 0);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 0);
        $productImage3 = ImageUtils::resizeImageToMaxWidthHeight($productImage3, 340, 340, 0);

        $productImage1 = \imagecropauto($productImage1, \IMG_CROP_SIDES);
        $productImage2 = \imagecropauto($productImage2, \IMG_CROP_SIDES);
        $productImage3 = \imagecropauto($productImage3, \IMG_CROP_SIDES);

        $productImage1Data = new RowCroppedImageData($productImage1);
        $productImage2Data = new RowCroppedImageData($productImage2);
        $productImage3Data = new RowCroppedImageData($productImage3);

        $productImageDatas = $this->createProductImageArraySortedByHeight($productImage1Data, $productImage2Data, $productImage3Data);

        $offsetX = RowCroppedUtils::calculateOffsetXForImagesBlock($productImageDatas);
        foreach ($productImageDatas as $productImageData){
            // TODO: current impl is for 4:2 ratio add own strategies for other ratios
            $offsetY = RowCroppedUtils::calculateOffsetYByTargetImageHeight($productImageData, self::$HEIGHT);
            RowCroppedUtils::copyImage($productImageData, $offsetX, $offsetY, $categoryImage);
            $offsetX += $productImageData->getWidth() + RowCroppedConstants::PADDING;
        }

        return $categoryImage;
    }

    /**
     * @param RowCroppedImageData[] $productImageDatas
     * @return RowCroppedImageData[]
     */
    private function createProductImageArraySortedByHeight(... $productImageDatas): array
    {
        RowCroppedUtils::sortImagesArrayByHeightAsc($productImageDatas);

        return array($productImageDatas[0], $productImageDatas[2], $productImageDatas[1]);
    }

}