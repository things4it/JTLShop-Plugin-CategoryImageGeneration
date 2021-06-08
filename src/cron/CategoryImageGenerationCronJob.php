<?php

namespace Plugin\t4it_category_image_generation\src\cron;

use JTL\Cron\Job;
use JTL\Cron\JobInterface;
use JTL\Cron\QueueEntry;
use JTL\Shop;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryCronJobQueueDao;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationServiceInterface;

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

            Shop::Cache()->flushTags(\CACHING_GROUP_CATEGORY);
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
        $categoryImageGenerationServiceInterface = Shop::Container()->get(CategoryImageGenerationServiceInterface::class);
        $categories = CategoryCronJobQueueDao::findByLimit($this->db, 120);
        foreach ($categories as $category) {
            try {
                $categoryImageGenerationServiceInterface->generateCategoryImage($category->getKKategorie());

                $this->logger->debug(sprintf('Category-Image-Generation-CronJob: Image for category %s created', $category->getKKategorie()));
            } catch (\Exception $e) {
                $this->logger->warning(sprintf('Category-Image-Generation-CronJob: Could not create image for category %s:  %s', $category->getKKategorie(), $e->getMessage()));
            }

            CategoryCronJobQueueDao::delete($category->getKKategorie(), $this->db);
        }
    }

}