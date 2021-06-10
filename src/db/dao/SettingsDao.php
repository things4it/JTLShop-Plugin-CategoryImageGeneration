<?php

namespace Plugin\t4it_category_image_generation\src\db\dao;

use JTL\DB\DbInterface;

class SettingsDao
{

    public static function fetchChangedFlag(DbInterface $db): bool
    {
        $changed = $db->selectSingleRow('xplugin_t4it_category_image_generation_settings', 'cKey', 'changedFlag');
        if ($changed != null) {
            return boolval($changed->cValue);
        }

        return false;
    }

    public static function updateChangedFlag(bool $changedFlag, DbInterface $db)
    {
        $db->upsert('xplugin_t4it_category_image_generation_settings', (object)['cKey' => 'changedFlag', 'cValue' => $changedFlag]);
    }

}