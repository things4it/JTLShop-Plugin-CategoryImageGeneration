<?php

namespace Plugin\t4it_category_image_generation\adminmenu;


use JTL\Alert\Alert;
use JTL\DB\DbInterface;
use JTL\Helpers\Form;
use JTL\Helpers\Request;
use JTL\Plugin\PluginInterface;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Plugin\t4it_category_image_generation\src\service\CategoryImageGenerationServiceInterface;

class RegenerateCategoryImageTab
{
    public static function handleRequest(PluginInterface $plugin, DbInterface $db, JTLSmarty $smarty)
    {
        // TODO: handle invalid token
        if (!empty($_POST) && Request::postVar('code') == 're-generate' && Form::validateToken()) {
            self::regenerateCategoryImage($categoryId = Request::postInt('categoryId'));
        }

        return self::displayTab($smarty, $plugin);
    }

    private static function regenerateCategoryImage(int $categoryId): void
    {
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

    /**
     * @param JTLSmarty $smarty
     * @param PluginInterface $plugin
     * @return string
     * @throws \SmartyException
     */
    private static function displayTab(JTLSmarty $smarty, PluginInterface $plugin): string
    {
        $smarty->assign('API_URL', $plugin->getPaths()->getAdminURL() . "/api.php");
        $smarty->assign('adminURL', Shop::getURL() . '/' . \PFAD_ADMIN . 'plugin.php?kPlugin=' . $plugin->getID());
        return $smarty->fetch($plugin->getPaths()->getAdminPath() . '/templates/re-generate.tpl');
    }
}