<?php

namespace Plugin\things4it_category_image_generation;

use JTL\Cron\Job;
use JTL\Cron\JobInterface;
use JTL\Cron\QueueEntry;
use Plugin\things4it_category_image_generation\CategoriesCronJobQueue\CategoryCronJobEntry;
use Plugin\things4it_category_image_generation\CategoriesCronJobQueue\CategoryCronJobQueueDao;
use Plugin\things4it_category_image_generation\CategoriesHelper\CategoryHelperDao;
use Plugin\things4it_category_image_generation\CategoriesHelper\Image;

/**
 * Class CategoryImageGenerationCronJob
 * @package Plugin\things4it_category_image_generation
 */
class CategoryImageGenerationCronJob extends Job
{

    /**
     * @inheritdoc
     */
    public function start(QueueEntry $queueEntry): JobInterface
    {
        parent::start($queueEntry);

        if ($queueEntry->taskLimit === 0) {
            $queueEntry->taskLimit = $this->initCronJobQueue();
            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: started - initialize queue with %s categories without image', $queueEntry->taskLimit));
        } else {
            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: continue queue processing - remaining categories: %s', $queueEntry->taskLimit));
        }

        $this->generateCategoryImagesForNextChunk();
        $queueEntry->taskLimit = CategoryCronJobQueueDao::count($this->db);
        if ($queueEntry->taskLimit == 0) {
            $this->logger->info('Category-Image-Generation-CronJob: finished');
            $this->setFinished(true);
        } else {
            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: chunk processed - %s categories in queue', $queueEntry->taskLimit));
        }

        return $this;
    }

    /**
     * @return int count of categories without image
     */
    private function initCronJobQueue(): int
    {
        $categoriesWithoutImage = CategoryHelperDao::findAllWithoutImage($this->db);
        foreach ($categoriesWithoutImage as $categoryWithoutImage) {
            CategoryCronJobQueueDao::save($categoryWithoutImage->getKKategorie(), $this->db);
        }

        return sizeof($categoriesWithoutImage);
    }

    private function generateCategoryImagesForNextChunk()
    {
        $categories = CategoryCronJobQueueDao::findByLimit($this->db, 120);
        foreach ($categories as $category) {
            $this->handleCategory($category);
        }
    }

    /**
     * @param CategoryCronJobEntry $category
     */
    private function handleCategory(CategoryCronJobEntry $category): void
    {
        $randomArticleImages = CategoryHelperDao::findRandomArticleImages($category->getKKategorie(), $this->db);
        $randomArticleImagesCount = sizeof($randomArticleImages);
        if ($randomArticleImagesCount > 0) {
            $categoryImage = $this->generateCategoryImage($randomArticleImagesCount, $randomArticleImages);
            $this->safeCategoryImageAndUpdateDb($category->getKKategorie(), $categoryImage);
            $this->logger->debug(sprintf('Category-Image-Generation-CronJob: Image for category %s created', $category->getKKategorie()));
        } else {
            $this->logger->debug(sprintf('Category-Image-Generation-CronJob: Could not create image for category %s - no articles with images found', $category->getKKategorie()));
        }

        CategoryCronJobQueueDao::delete($category->getKKategorie(), $this->db);
    }

    /**
     * @param int $randomArticleImagesCount
     * @param Image[] $randomArticleImages
     * @return false|\GdImage|resource
     */
    private function generateCategoryImage(int $randomArticleImagesCount, array $randomArticleImages)
    {
        $categoryImage = $this->createTransparentImage(1024, 1024);

        if ($randomArticleImagesCount == 3) {
            $imageNumber = 0;
            foreach ($randomArticleImages as $randomArticleImage) {
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->getCPath();
                if (\file_exists($sourceImagePath)) {
                    $image = $this->getResizedArticleImage($sourceImagePath, 500, 500);
                    if ($imageNumber == 0) {
                        \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    } elseif ($imageNumber == 1) {
                        \imagecopy($categoryImage, $image, 500, 24, 0, 0, imagesx($image), imagesy($image));
                    } else {
                        \imagecopy($categoryImage, $image, 250, 500 + 24, 0, 0, imagesx($image), imagesy($image));
                    }

                    \imagedestroy($image);
                }

                $imageNumber++;
            }
        } elseif ($randomArticleImagesCount == 2) {
            $imageNumber = 0;
            foreach ($randomArticleImages as $randomArticleImage) {
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->getCPath();
                if (\file_exists($sourceImagePath)) {
                    $image = $this->getResizedArticleImage($sourceImagePath, 500, 500);
                    if ($imageNumber == 0) {
                        \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    } else {
                        \imagecopy($categoryImage, $image, 500, 500, 0, 0, imagesx($image), imagesy($image));
                    }

                    \imagedestroy($image);
                }

                $imageNumber++;
            }
        } elseif ($randomArticleImagesCount == 1) {
            $randomArticleImage = $randomArticleImages[0];

            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->getCPath();
            if (\file_exists($sourceImagePath)) {
                $image = $this->getResizedArticleImage($sourceImagePath, 1024, 1024);
                \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                \imagedestroy($image);
            }

        }

        return $categoryImage;
    }

    /**
     * @param int $width
     * @param int $height
     * @return false|\GdImage|resource
     */
    private function createTransparentImage(int $width, int $height)
    {
        $image = \imagecreatetruecolor($width, $height);
        $colorTransparent = \imagecolorallocatealpha($image, 0, 0, 0, 127);
        \imagefill($image, 0, 0, $colorTransparent);
        \imagealphablending($image, true);
        \imagesavealpha($image, true);

        return $image;
    }


    /**
     * @param string $originalImagePath
     * @param int $targetWidth
     * @param int $targetHeight
     * @return false|\GdImage|resource
     */
    private function getResizedArticleImage(string $originalImagePath, int $targetWidth = 640, int $targetHeight = 640)
    {
        list($originalImageWidth, $originalImageHeight, $originalImageType) = \getimagesize($originalImagePath);
        switch ($originalImageType) {
            case \IMAGETYPE_GIF:
                $imageOriginal = \imagecreatefromgif($originalImagePath);
                break;
            case \IMAGETYPE_PNG:
                $imageOriginal = \imagecreatefrompng($originalImagePath);
                break;
            case \IMAGETYPE_JPEG:
            default:
                $imageOriginal = \imagecreatefromjpeg($originalImagePath);
                break;
        }

        if ($originalImageWidth > $originalImageHeight) {
            $scale = $targetWidth / $originalImageWidth;
        } else {
            $scale = $targetHeight / $originalImageHeight;
        }

        $newWidth = $originalImageWidth * $scale;
        $newHeight = $originalImageHeight * $scale;

        $offsetX = ($targetWidth - $newWidth) / 2;
        $offsetY = ($targetHeight - $newHeight) / 2;

        $imageResized = $this->createTransparentImage($targetWidth, $targetHeight);
        \imagecopyresampled($imageResized, $imageOriginal, $offsetX, $offsetY, 0, 0, $newWidth, $newHeight, $originalImageWidth, $originalImageHeight);
        \imagedestroy($imageOriginal);

        return $imageResized;
    }

    /**
     * @param int $categoryId
     * @param object $categoryImage
     */
    private function safeCategoryImageAndUpdateDb(int $categoryId, $categoryImage): void
    {
        $targetImageName = Bootstrap::CATEGORY_IMAGE_NAME_PREFIX . $categoryId . '.png';
        $targetImagePath = \PFAD_ROOT . \STORAGE_CATEGORIES . $targetImageName;
        \imagecropauto($categoryImage);
        \imagepng($categoryImage, $targetImagePath);
        \imagedestroy($categoryImage);

        CategoryHelperDao::saveCategoryImage($categoryId, $targetImagePath, $this->db);
    }

}