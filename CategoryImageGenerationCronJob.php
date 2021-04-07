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
     * TODO:
     *  - update ... upsert (insert/update) ...
     *  - select random image
     *  - support multiple images ...     *
     *
     * @return bool
     */
    private function resolveAndPersistCategoryImagesBasedOnArticles(): bool
    {
        $categories = $this->db->query("
            SELECT
	            k.kKategorie
            FROM
	            tkategorie k",
            ReturnType::ARRAY_OF_OBJECTS);

        foreach ($categories as $category) {
            $randomArticleImagesForCategory = $this->db->query('
                SELECT
                    ka.kKategorie,
                    ap.kBild,
                    b.cPfad
                FROM
                    tartikel a
                JOIN 
                    tkategorieartikel ka
                    ON ka.kArtikel = a.kArtikel
                        AND ka.kKategorie = "' . $category->kKategorie . '"
                JOIN
                    tartikelpict ap
                    ON ap.kArtikel = a.kArtikel
                JOIN tbild b
                    ON b.kbild = ap.kbild
                ORDER BY RAND()
                LIMIT 3',
                ReturnType::ARRAY_OF_OBJECTS);

            $categoryImage = imagecreatetruecolor(1024, 1024);
            $colorTransparent = imagecolorallocatealpha($categoryImage, 0, 0, 0, 127);
            imagefill($categoryImage, 0, 0, $colorTransparent);
            imagealphablending($categoryImage, true);
            imagesavealpha($categoryImage, true);

            $i = 0;
            foreach ($randomArticleImagesForCategory as $randomArticleImage) {
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $randomArticleImage->cPfad;
                if (\file_exists($sourceImagePath)) {
                    $image = $this->getResizedArticleImage($sourceImagePath, 500, 500);
                    if ($i == 0) {
                        \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    } else if ($i == 1) {
                        \imagecopy($categoryImage, $image, 500, 24, 0, 0, imagesx($image), imagesy($image));
                    } else {
                        \imagecopy($categoryImage, $image, 250, 500 + 24, 0, 0, imagesx($image), imagesy($image));
                    }

                    \imagedestroy($image);
                }

                $i++;
            }

            $targetImageName = 'things4it_category_image_generation_' . $category->kKategorie . '.png';
            $targetImagePath = \PFAD_ROOT . \STORAGE_CATEGORIES . $targetImageName;
            \imagecropauto($categoryImage);
            \imagepng($categoryImage, $targetImagePath);
            \imagedestroy($categoryImage);

            $this->db->executeQuery(
                'INSERT INTO
                    tkategoriepict
                  SET
                    kKategorie=' . $category->kKategorie . ',
                    cPfad="' . $targetImageName . '"',
                ReturnType::QUERYSINGLE
            );
        }

        return true;
    }

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
        $imageOriginal = \imagecreatefromjpeg($sourceImagePath);
        \imagecopyresampled($imageResized, $imageOriginal, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
        \imagedestroy($imageOriginal);

        return $imageResized;
    }

}