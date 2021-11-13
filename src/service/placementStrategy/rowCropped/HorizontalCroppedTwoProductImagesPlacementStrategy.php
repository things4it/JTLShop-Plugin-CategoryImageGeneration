<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class HorizontalCroppedTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    public static function getName(): string
    {
        return __("admin.settings.image-strategy.horizontal-cropped");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "horizontal-cropped-two";
    }

    public function placeProductImages($categoryImage, ImageRatio $imageRatio, $productImage1, $productImage2)
    {
        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, 340, 340, 0);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, 340, 340, 0);

        $productImage1 = \imagecropauto($productImage1, \IMG_CROP_SIDES);
        $productImage2 = \imagecropauto($productImage2, \IMG_CROP_SIDES);

        $productImages = $this->createProductImageArraySortedByHeight($productImage1, $productImage2);

        $offsetY = HorizontalCroppedUtils::calculateOffsetYByRatio($imageRatio);
        $offsetX = HorizontalCroppedUtils::calculateOffsetXForImagesBlock($productImages);
        foreach ($productImages as $productImage){
            HorizontalCroppedUtils::copyImage($productImage, $offsetX, $offsetY, $categoryImage);
            $offsetX += imagesx($productImage) + HorizontalCroppedConstants::PADDING;
        }
    }

    /**
     * @param ...$productImages
     * @return array
     */
    private function createProductImageArraySortedByHeight(... $productImages): array
    {
        HorizontalCroppedUtils::sortImagesArrayByHeightAsc($productImages);

        return array($productImages[0], $productImages[1]);
    }
}