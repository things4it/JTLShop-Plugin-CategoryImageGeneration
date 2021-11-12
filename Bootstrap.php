<?php declare(strict_types=1);
/**
 * @package Plugin\things4it_category_image_generation
 * @author  Johannes Wendig
 */

namespace Plugin\t4it_category_image_generation;

use JTL\Alert\Alert;
use JTL\Events\Dispatcher;
use JTL\Events\Event;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Plugin\t4it_category_image_generation\adminmenu\RegenerateCategoryImageTab;
use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\cron\CategoryImageGenerationCronJob;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\db\dao\SettingsDao;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationService;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationServiceInterface;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\HorizontalOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\HorizontalThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\HorizontalTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\utils\CategoryImageGenerator;

/**
 * Class Bootstrap
 * @package Plugin\things4it_category_image_generation
 */
class Bootstrap extends Bootstrapper
{

    /**
     * @inheritdoc
     */
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);

        $this->setupContainer();
        $this->addListeners($dispatcher);
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
    public function disabled()
    {
        parent::disabled();

        CategoryHelperDao::removeGeneratedImages($this->getDB());
        CategoryImageGenerator::removeGeneratedImages();
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

    /**
     * @inheritdoc
     */
    public function renderAdminMenuTab(string $tabName, int $menuID, JTLSmarty $smarty): string
    {
        $plugin = $this->getPlugin();

        if ($tabName === 'Bild neu generieren (einzeln)') {
            return RegenerateCategoryImageTab::handleRequest($plugin, $this->getDB(), $smarty);
        }

        return parent::renderAdminMenuTab($tabName, $menuID, $smarty);
    }

    private function setupContainer(): void
    {
        $container = Shop::Container();
        $container->setFactory(CategoryImageGenerationServiceInterface::class, function ($container) {
            return new CategoryImageGenerationService($this->getDB(), $this->getPlugin());
        });

        $this->provideImagePlacementStrategiesOffset($container);
        $this->provideImagePlacementStrategiesFlippedOffset($container);
        $this->provideImagePlacementStrategiesRow($container);
    }

    /**
     * @param Dispatcher $dispatcher
     */
    private function addListeners(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(Event::MAP_CRONJOB_TYPE, static function (array $args) {
            if ($args['type'] === Constants::CRON_JOB_CATEGORY_IMAGE_GENERATION) {
                $args['mapping'] = CategoryImageGenerationCronJob::class;
            }
        });

        $dispatcher->listen(Event::GET_AVAILABLE_CRONJOBS, static function (array $args) {
            $jobs = &$args['jobs'];
            if (is_array($jobs)) {
                array_push($jobs, Constants::CRON_JOB_CATEGORY_IMAGE_GENERATION);
            }
        });

        $dispatcher->listen('shop.hook.' . \HOOK_PLUGIN_SAVE_OPTIONS, function (array $args) {
            $hasError = $args['hasError'];
            $savedPlugin = $args['plugin'];

            if ($savedPlugin->getID() == $this->getPlugin()->getID() && $hasError === false) {
                Shop::Container()->getAlertService()->addAlert(Alert::TYPE_SUCCESS, __('admin.settings.post-saved.success'), 'infoSettingsChanged');
                SettingsDao::updateChangedFlag(true, $this->getDB());
            }
        });
    }

    private function addCron(): void
    {
        $job = new \stdClass();
        $job->name = 'Kategorie-Bild generierung';
        $job->jobType = Constants::CRON_JOB_CATEGORY_IMAGE_GENERATION;
        $job->frequency = 24;
        $job->startDate = 'NOW()';
        $job->startTime = '00:00:00';

        $this->getDB()->insert('tcron', $job);
    }

    private function removeCron(): void
    {
        $this->getDB()->delete('tcron', 'jobType', Constants::CRON_JOB_CATEGORY_IMAGE_GENERATION);
    }

    private function provideImagePlacementStrategiesOffset(\JTL\Services\DefaultServicesInterface $container): void
    {
        $container->setFactory(OffsetOneProductImagePlacementStrategy::getCode(), function ($container) {
            return new OffsetOneProductImagePlacementStrategy();
        });

        $container->setFactory(OffsetTwoProductImagesPlacementStrategy::getCode(), function ($container) {
            return new OffsetTwoProductImagesPlacementStrategy();
        });

        $container->setFactory(OffsetThreeProductImagesPlacementStrategy::getCode(), function ($container) {
            return new OffsetThreeProductImagesPlacementStrategy();
        });
    }

    private function provideImagePlacementStrategiesRow(\JTL\Services\DefaultServicesInterface $container): void
    {
        $container->setFactory(HorizontalOneProductImagePlacementStrategy::getCode(), function ($container) {
            return new HorizontalOneProductImagePlacementStrategy();
        });

        $container->setFactory(HorizontalTwoProductImagesPlacementStrategy::getCode(), function ($container) {
            return new HorizontalTwoProductImagesPlacementStrategy();
        });

        $container->setFactory(HorizontalThreeProductImagesPlacementStrategy::getCode(), function ($container) {
            return new HorizontalThreeProductImagesPlacementStrategy();
        });
    }

    private function provideImagePlacementStrategiesFlippedOffset(\JTL\Services\DefaultServicesInterface $container): void
    {
        $container->setFactory(FlippedOffsetOneProductImagePlacementStrategy::getCode(), function ($container) {
            return new FlippedOffsetOneProductImagePlacementStrategy();
        });

        $container->setFactory(FlippedOffsetTwoProductImagesPlacementStrategy::getCode(), function ($container) {
            return new FlippedOffsetTwoProductImagesPlacementStrategy();
        });

        $container->setFactory(FlippedOffsetThreeProductImagesPlacementStrategy::getCode(), function ($container) {
            return new FlippedOffsetThreeProductImagesPlacementStrategy();
        });
    }

}
