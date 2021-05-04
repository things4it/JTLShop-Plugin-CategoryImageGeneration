<?php

namespace Plugin\t4it_category_image_generation;

use JTL\Cron\Job;
use JTL\Cron\JobInterface;
use JTL\Cron\QueueEntry;
use Plugin\t4it_category_image_generation\CategoriesCronJobQueue\CategoryCronJobEntry;
use Plugin\t4it_category_image_generation\CategoriesCronJobQueue\CategoryCronJobQueueDao;
use Plugin\t4it_category_image_generation\CategoriesHelper\CategoryHelperDao;
use Plugin\t4it_category_image_generation\CategoriesHelper\CategoryImageGenerator;

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
            $categoryImagePath = CategoryImageGenerator::generateCategoryImage($category->getKKategorie(), $randomArticleImages);
            CategoryHelperDao::saveCategoryImage($category->getKKategorie(), $categoryImagePath, $this->db);

            $this->logger->debug(sprintf('Category-Image-Generation-CronJob: Image for category %s created', $category->getKKategorie()));
        } else {
            $this->logger->debug(sprintf('Category-Image-Generation-CronJob: Could not create image for category %s - no articles with images found', $category->getKKategorie()));
        }

        CategoryCronJobQueueDao::delete($category->getKKategorie(), $this->db);
    }

}