<?php

namespace Plugin\t4it_category_image_generation\src\cron;

use JTL\Cron\Job;
use JTL\Cron\JobInterface;
use JTL\Cron\QueueEntry;
use JTL\Shop;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryCronJobQueueDao;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\db\dao\SettingsDao;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationServiceInterface;
use Plugin\t4it_category_image_generation\src\utils\CategoryImageGenerator;

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

        $queueTimeStart = microtime(true);

        if ($queueEntry->taskLimit === 0) {
            if (SettingsDao::fetchChangedFlag($this->db)) {
                $this->logger->info('Category-Image-Generation-CronJob: settings-changed-flag detected - delete all previously generated images to regenerate it');

                CategoryHelperDao::removeGeneratedImages($this->db);
                CategoryImageGenerator::removeGeneratedImages();

                SettingsDao::updateChangedFlag(false, $this->db);
            }

            $queueEntry->taskLimit = $this->initCronJobQueue();
            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: started - initialize queue with %s categories without image', $queueEntry->taskLimit));
        } else {
            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: continue queue processing - remaining categories: %s', $queueEntry->taskLimit));
        }

        $generatedImagesCount = $this->generateCategoryImagesForNextChunk($queueTimeStart);

        $queueEntry->taskLimit = CategoryCronJobQueueDao::count($this->db);
        if ($queueEntry->taskLimit == 0) {
            Shop::Cache()->flushTags(\CACHING_GROUP_CATEGORY);
            $this->setFinished(true);

            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: finished - last chunk processed %s categories', $generatedImagesCount));
        } else {
            $this->logger->info(\sprintf('Category-Image-Generation-CronJob: chunk processed %s categories - %s categories left in queue', $generatedImagesCount, $queueEntry->taskLimit));
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

    /**
     * @param float $queueTimeStart
     * @return int count of generated images/processed categories
     */
    private function generateCategoryImagesForNextChunk(float $queueTimeStart): int
    {
        $generatedImagesCount = 0;

        $categoryImageGenerationServiceInterface = Shop::Container()->get(CategoryImageGenerationServiceInterface::class);
        $categories = CategoryCronJobQueueDao::findAll($this->db);
        foreach ($categories as $category) {
            try {
                $categoryImageGenerationServiceInterface->generateCategoryImage($category->getKKategorie());

                $this->logger->debug(sprintf('Category-Image-Generation-CronJob: Image for category %s created', $category->getKKategorie()));
            } catch (\Exception $e) {
                $this->logger->warning(sprintf('Category-Image-Generation-CronJob: Could not create image for category %s:  %s', $category->getKKategorie(), $e->getMessage()));
            }

            CategoryCronJobQueueDao::delete($category->getKKategorie(), $this->db);

            $generatedImagesCount++;

            if ($this->calculateSecondsBeforePhpMaxExecutionTimeReached($queueTimeStart) < 20) {
                $this->logger->debug('Category-Image-Generation-CronJob: Abort image generation before php-execution-time limit reached');
                break;
            }
        }

        return $generatedImagesCount;
    }

    private function calculateSecondsBeforePhpMaxExecutionTimeReached($timeStart): float
    {
        $maxExecutionTime = ini_get('max_execution_time');

        $currentTime = microtime(true);
        $currentExecutionTime = $currentTime - $timeStart;

        return $maxExecutionTime - $currentExecutionTime;
    }

}