<?php

namespace Plugin\t4it_category_image_generation\src\db\dao;

use DbInterface;
use JTL\DB\ReturnType;
use Plugin\t4it_category_image_generation\src\db\entity\Category;

class CategoryCronJobQueueDao
{
    /**
     * @param DbInterface $db
     * @return Category[]
     */
    public static function findAll(DbInterface $db): array
    {
        $resultObjects = $db->selectAll('xplugin_t4it_category_image_generation_job_queue', [], []);

        $categoriesWithoutImage = array();
        foreach ($resultObjects as $resultObject) {
            $categoryWithoutImage = new Category();
            $categoryWithoutImage->setKKategorie($resultObject->kKategorie);

            array_push($categoriesWithoutImage, $categoryWithoutImage);
        }

        return $categoriesWithoutImage;
    }

    public static function count(DbInterface $db)
    {
        $result = $db->queryPrepared('SELECT COUNT(*) AS count FROM xplugin_t4it_category_image_generation_job_queue', [], ReturnType::SINGLE_OBJECT);
        return (int)$result->count;
    }

    public static function save(int $kKategorie, DbInterface $db)
    {
        $db->upsert('xplugin_t4it_category_image_generation_job_queue', (object)['kKategorie' => $kKategorie]);
    }

    public static function delete(int $kKategorie, DbInterface $db)
    {
        $db->delete('xplugin_t4it_category_image_generation_job_queue', 'kKategorie', $kKategorie);
    }
}