<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowCroppedTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-cropped-two";
    }

    /**
     * @param $categoryImage
     * @param ImageRatio $imageRatio
     * @param $productImage1
     * @param $productImage2
     */
    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2)
    {
        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 0);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 0);

        $productImage1 = \imagecropauto($productImage1, \IMG_CROP_SIDES);
        $productImage2 = \imagecropauto($productImage2, \IMG_CROP_SIDES);

        $productImages = $this->createProductImageArraySortedByHeight($productImage1, $productImage2);

        $offsetX = RowCroppedUtils::calculateOffsetXForImagesBlock($productImages);
        foreach ($productImages as $productImage){
            $offsetY = RowCroppedUtils::calculateOffsetYByRatio($productImage, $imageRatio);
            RowCroppedUtils::copyImage($productImage, $offsetX, $offsetY, $categoryImage);
            $offsetX += imagesx($productImage) + RowCroppedConstants::PADDING;
        }
    }

    /**
     * @param ...$productImages
     * @return array
     */
    private function createProductImageArraySortedByHeight(... $productImages): array
    {
        RowCroppedUtils::sortImagesArrayByHeightAsc($productImages);

        return array($productImages[0], $productImages[1]);
    }
}