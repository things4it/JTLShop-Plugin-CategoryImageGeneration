<?php

namespace Plugin\things4it_category_image_generation;

use JTL\Cron\Job;
use JTL\Cron\JobInterface;
use JTL\Cron\QueueEntry;
use JTL\DB\ReturnType;

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

        $this->logger->debug('Category-Image-Generation-CronJob started');
        $this->resolveAndPersistCategoryImagesBasedOnArticles();
        $this->logger->debug('Category-Image-Generation-CronJob finished.');

        $this->setFinished(true);
        return $this;
    }

    /**
     * @return bool
     */
    private function resolveAndPersistCategoryImagesBasedOnArticles(): bool
    {
        $categories = $this->db->query("
            SELECT
	            k.kKategorie
            FROM
	            tkategorie k
            LEFT OUTER JOIN tkategoriepict kp
	            ON kp.kKategorie = k.kKategorie
	        WHERE
	            kp.kKategoriePict IS NULL",
            ReturnType::ARRAY_OF_OBJECTS);

        foreach ($categories as $category) {
            $this->handleCategory($category->kKategorie);
        }

        return true;
    }

    /**
     * @param int $categoryId
     */
    private function handleCategory(int $categoryId): void
    {
        $randomArticleImages = $this->fetchRandomArticleImages($categoryId);
        $randomArticleImagesCount = sizeof($randomArticleImages);
        if ($randomArticleImagesCount > 0) {
            $categoryImage = $this->generateCategoryImage($randomArticleImagesCount, $randomArticleImages);
            $this->safeCategoryImageAndUpdateDb($categoryId, $categoryImage);
        }
    }

    /**
     * @param int $categoryId
     * @return array
     */
    private function fetchRandomArticleImages(int $categoryId): array
    {
        $categoryPaths = $this->db->query("
            SELECT
                k1.kKategorie k1,
                k2.kKategorie k2,
                k3.kKategorie k3,
                k4.kKategorie k4,
                k5.kKategorie k5
            FROM tkategorie k1
            LEFT JOIN tkategorie k2 ON k2.kOberKategorie = k1.kKategorie
            LEFT JOIN tkategorie k3 ON k3.kOberKategorie = k2.kKategorie	
            LEFT JOIN tkategorie k4 ON k4.kOberKategorie = k3.kKategorie		
            LEFT JOIN tkategorie k5 ON k5.kOberKategorie = k4.kKategorie			
            WHERE
                k1.kKategorie = '" . $categoryId . "'",
            ReturnType::ARRAY_OF_OBJECTS);

        $categoryIds = [];
        foreach ($categoryPaths as $categoryPath) {
            array_push($categoryIds, $categoryPath->k1);
            array_push($categoryIds, $categoryPath->k2);
            array_push($categoryIds, $categoryPath->k3);
            array_push($categoryIds, $categoryPath->k4);
            array_push($categoryIds, $categoryPath->k5);
        }
        $categoryIds = array_filter($categoryIds);
        $categoryIds = array_unique($categoryIds);

        return $this->db->query("
                SELECT
                     ka.kKategorie,
                     b.cPfad
                FROM tartikel a
                JOIN tartikelpict ap ON ap.kArtikel = a.kArtikel
                JOIN tbild b ON b.kBild = ap.kBild
                JOIN tkategorieartikel ka ON ka.kArtikel = a.kArtikel
                WHERE
                    ka.kKategorie IN (" . join(',', $categoryIds) . ")
                ORDER BY RAND()
                LIMIT 3",
            ReturnType::ARRAY_OF_OBJECTS);
    }

    /**
     * @param int $randomArticleImagesCount
     * @param array $randomArticleImages
     * @return false|\GdImage|resource
     */
    private function generateCategoryImage(int $randomArticleImagesCount, array $randomArticleImages)
    {
        $categoryImage = $this->createBlankCategoryImage();

        if ($randomArticleImagesCount == 3) {
            $imageNumber = 0;
            foreach ($randomArticleImages as $randomArticleImage) {
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->cPfad;
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
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->cPfad;
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

            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->cPfad;
            if (\file_exists($sourceImagePath)) {
                $image = $this->getResizedArticleImage($sourceImagePath, 1024, 1024);
                \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                \imagedestroy($image);
            }

        }

        return $categoryImage;
    }


    /**
     * @return false|\GdImage|resource
     */
    private function createBlankCategoryImage()
    {
        $categoryImage = \imagecreatetruecolor(1024, 1024);
        $colorTransparent = \imagecolorallocatealpha($categoryImage, 0, 0, 0, 127);
        \imagefill($categoryImage, 0, 0, $colorTransparent);
        \imagealphablending($categoryImage, true);
        \imagesavealpha($categoryImage, true);

        return $categoryImage;
    }


    /**
     * @param string $sourceImagePath
     * @param int $width
     * @param int $height
     * @return false|\GdImage|resource
     */
    private function getResizedArticleImage(string $sourceImagePath, int $width = 640, int $height = 640)
    {
        list($sourceWidth, $sourceHeight) = \getimagesize($sourceImagePath);

        $sourceRatio = $sourceWidth / $sourceHeight;
        if ($width / $height > $sourceRatio) {
            $width = $height * $sourceRatio;
        } else {
            $height = $width / $sourceRatio;
        }

        $imageResized = \imagecreatetruecolor($width, $height);
        $imageOriginalInfo = \getimagesize($sourceImagePath);
        switch ($imageOriginalInfo[2]) {
            case \IMAGETYPE_GIF:
                $imageOriginal = \imagecreatefromgif($sourceImagePath);
                break;
            case \IMAGETYPE_PNG:
                $imageOriginal = \imagecreatefrompng($sourceImagePath);
                break;
            case \IMAGETYPE_JPEG:
            default:
                $imageOriginal = \imagecreatefromjpeg($sourceImagePath);
                break;
        }

        \imagecopyresampled($imageResized, $imageOriginal, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
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

        $this->db->insert('tkategoriepict', (object)[
            'cPfad' => $targetImageName,
            'kKategorie' => $categoryId
        ]);
    }

}