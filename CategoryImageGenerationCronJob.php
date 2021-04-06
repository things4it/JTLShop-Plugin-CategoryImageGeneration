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
        $categoriesToImage = $this->db->query(
            'SELECT 
                    k.kKategorie,
                    ap.kBild,
                    b.cPfad
                FROM tkategorie k
                JOIN tartikelpict ap    
                    ON ap.kArtikel = (
                        SELECT 
                            MIN(ka_inner.kArtikel)
                        FROM tkategorieartikel ka_inner
                        WHERE
                            ka_inner.kKategorie = k.kKategorie
                    )
                JOIN tbild b
                    ON b.kbild = ap.kbild',
            ReturnType::ARRAY_OF_OBJECTS);

        foreach ($categoriesToImage as $categoryToImage) {
            $targetImageName = 'things4it_category_image_generation_' . $categoryToImage->kKategorie . '.jpg';

            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $categoryToImage->cPfad;
            $targetImagePath = \PFAD_ROOT . \STORAGE_CATEGORIES . $targetImageName;

            if (\file_exists($sourceImagePath)) {
                \copy($sourceImagePath, $targetImagePath);
            }

            $this->db->executeQuery(
                'INSERT INTO
                    tkategoriepict
                  SET
                    kKategorie=' . $categoryToImage->kKategorie . ',
                    cPfad="' . $targetImageName . '"',
                ReturnType::QUERYSINGLE
            );
        }

        return true;
    }

}