<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowCroppedThreeProductImagesPlacementStrategy implements ThreeProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-cropped-three";
    }

    /**
     * @param $categoryImage
     * @param ImageRatio $imageRatio
     * @param $productImage1
     * @param $productImage2
     * @param $productImage3
     */
    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2, $productImage3)
    {
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
            $offsetY = RowCroppedUtils::calculateOffsetYByRatio($productImageData, $imageRatio);
            RowCroppedUtils::copyImage($productImageData, $offsetX, $offsetY, $categoryImage);
            $offsetX += $productImageData->getWidth() + RowCroppedConstants::PADDING;
        }
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