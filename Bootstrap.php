<?php declare(strict_types=1);
/**
 * @package Plugin\things4it_category_image_generation
 * @author  Johannes Wendig
 */

namespace Plugin\t4it_category_image_generation;

use JTL\Events\Dispatcher;
use JTL\Events\Event;
use JTL\Plugin\Bootstrapper;
use Plugin\t4it_category_image_generation\CategoriesHelper\CategoryHelperDao;
use Plugin\t4it_category_image_generation\CategoriesHelper\CategoryImageGenerator;

/**
 * Class Bootstrap
 * @package Plugin\things4it_category_image_generation
 */
class Bootstrap extends Bootstrapper
{

    private const CATEGORY_IMAGE_GENERATION_CRON_JOB = 'things4it_category_image_generation_cronjob';

    /**
     * @inheritdoc
     */
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);

        $dispatcher->listen(Event::MAP_CRONJOB_TYPE, static function (array $args) {
            if ($args['type'] === self::CATEGORY_IMAGE_GENERATION_CRON_JOB) {
                $args['mapping'] = CategoryImageGenerationCronJob::class;
            }
        });

        $dispatcher->listen(Event::GET_AVAILABLE_CRONJOBS, static function (array $args) {
            $jobs = &$args['jobs'];
            if (is_array($jobs)) {
                array_push($jobs, self::CATEGORY_IMAGE_GENERATION_CRON_JOB);
            }
        });


    }

    /**
     * @inheritdoc
     */
    public function installed()
    {
        parent::installed();
        $this->addCron();
    }

    /**
     * @inheritdoc
     */
    public function uninstalled(bool $deleteData = true)
    {
        parent::uninstalled($deleteData);
        $this->removeCron();

        if ($deleteData) {
            CategoryHelperDao::removeGeneratedImages($this->getDB());
            CategoryImageGenerator::removeGeneratedImages();
        }
    }


    private function addCron(): void
    {
        $job = new \stdClass();
        $job->name = 'Kategorie-Bild generierung';
        $job->jobType = self::CATEGORY_IMAGE_GENERATION_CRON_JOB;
        $job->frequency = 24;
        $job->startDate = 'NOW()';
        $job->startTime = '00:00:00';

        $this->getDB()->insert('tcron', $job);
    }

    private function removeCron(): void
    {
        $this->getDB()->delete('tcron', 'jobType', self::CATEGORY_IMAGE_GENERATION_CRON_JOB);
    }

}
