<?php declare(strict_types=1);
/**
 * @package Plugin\things4it_category_image_generation
 * @author  Johannes Wendig
 */

namespace Plugin\t4it_category_image_generation;

use JTL\Alert\Alert;
use JTL\Events\Dispatcher;
use JTL\Events\Event;
use JTL\Helpers\Form;
use JTL\Helpers\Request;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Plugin\t4it_category_image_generation\src\cron\CategoryImageGenerationCronJob;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\db\dao\SettingsDao;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationService;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationServiceInterface;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ThreeProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\CategoryImageGenerator;

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

        $container = Shop::Container();
        $container->setFactory(CategoryImageGenerationServiceInterface::class, function ($container) {
            return new CategoryImageGenerationService($this->getDB());
        });

        $container->setFactory(OneProductImagePlacementStrategyInterface::class, function ($container) {
            return new DefaultOneProductImagePlacementStrategy();
        });

        $container->setFactory(TwoProductImagePlacementStrategyInterface::class, function ($container) {
            return new DefaultTwoProductImagesPlacementStrategy();
        });

        $container->setFactory(ThreeProductImagePlacementStrategyInterface::class, function ($container) {
            return new DefaultThreeProductImagesPlacementStrategy();
        });

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

        $dispatcher->listen('shop.hook.' . \HOOK_PLUGIN_SAVE_OPTIONS, function (array $args) {
            $hasError = $args['hasError'];
            $savedPlugin = $args['plugin'];

            if ($savedPlugin->getID() == $this->getPlugin()->getID() && $hasError === false) {
                Shop::Container()->getAlertService()->addAlert(Alert::TYPE_SUCCESS, __('admin.settings.post-saved.success'), 'infoSettingsChanged');
                SettingsDao::updateChangedFlag(true, $this->getDB());
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
        $smarty->assign('menuID', $menuID)->assign('posted', null);
        $plugin = $this->getPlugin();

        // TODO: extract into "controller/handler" !?
        if ($tabName === 'Bild neu generieren (einzeln)') {
            // TODO: handle invalid token
            if (!empty($_POST) && Request::postVar('code') == 're-generate' && Form::validateToken()) {
                $categoryId = Request::postInt('categoryId');

                // TODO: validate given categoryId ...

                try {
                    $categoryImageGenerationServiceInterface = Shop::Container()->get(CategoryImageGenerationServiceInterface::class);
                    $categoryImageGenerationServiceInterface->generateCategoryImage($categoryId);

                    Shop::Cache()->flushTags(\CACHING_GROUP_CATEGORY);

                    Shop::Container()->getAlertService()->addAlert(Alert::TYPE_SUCCESS, __('admin.regenerate.common.success', $categoryId), 'succReGenerate');
                } catch (\Exception $e) {
                    Shop::Container()->getAlertService()->addAlert(Alert::TYPE_ERROR, __('admin.regenerate.common.error', $categoryId, $e->getMessage()), 'errReGenerate');
                }
            }
        }

        $smarty->assign('API_URL', $plugin->getPaths()->getAdminURL() . "/api.php");

        return $smarty->assign('adminURL', Shop::getURL() . '/' . \PFAD_ADMIN . 'plugin.php?kPlugin=' . $plugin->getID()
        )->fetch($this->getPlugin()->getPaths()->getAdminPath() . '/templates/re-generate.tpl');
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
