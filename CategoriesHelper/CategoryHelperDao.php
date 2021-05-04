<?php

namespace Plugin\t4it_category_image_generation\CategoriesHelper;

use DbInterface;
use JTL\DB\ReturnType;
use Plugin\t4it_category_image_generation\Bootstrap;

class CategoryHelperDao
{
    /**
     * @param DbInterface $db
     * @return Category[]
     */
    public static function findAllWithoutImage(DbInterface $db): array
    {
        $resultObjects = $db->queryPrepared('
            SELECT
	            k.kKategorie
            FROM
	            tkategorie k
            LEFT OUTER JOIN tkategoriepict kp
	            ON kp.kKategorie = k.kKategorie
	        WHERE
	            kp.kKategoriePict IS NULL',
            [], ReturnType::ARRAY_OF_OBJECTS);

        $categoriesWithoutImage = array();
        foreach ($resultObjects as $resultObject) {
            $categoryWithoutImage = new Category();
            $categoryWithoutImage->setKKategorie($resultObject->kKategorie);

            array_push($categoriesWithoutImage, $categoryWithoutImage);
        }

        return $categoriesWithoutImage;
    }

    /**
     * @param int $categoryId
     * @param DbInterface $db
     * @return Image[]
     */
    public static function findRandomArticleImages(int $categoryId, DbInterface $db): array
    {
        $categoryPathsResult = $db->queryPrepared('
            SELECT
                k1.kKategorie k1,
                k2.kKategorie k2,
                k3.kKategorie k3,
                k4.kKategorie k4,
                k5.kKategorie k5
            FROM tkategorie k1
            LEFT JOIN tkategorie k2 ON k2.kOberKategorie = k1.kKategorie
            LEFT JOIN tkategorie k3 ON k3.kOberKategorie = k2.kKategorie	
            LEFT JOIN tkategorie k4 ON k4.kOberKategorie = k3.kKategorie		
            LEFT JOIN tkategorie k5 ON k5.kOberKategorie = k4.kKategorie			
            WHERE
                k1.kKategorie = :categoryId',
            ['categoryId' => $categoryId],
            ReturnType::ARRAY_OF_OBJECTS);

        $relevantCategoryIds = [];
        foreach ($categoryPathsResult as $categoryPathResult) {
            array_push($relevantCategoryIds, $categoryPathResult->k1);
            array_push($relevantCategoryIds, $categoryPathResult->k2);
            array_push($relevantCategoryIds, $categoryPathResult->k3);
            array_push($relevantCategoryIds, $categoryPathResult->k4);
            array_push($relevantCategoryIds, $categoryPathResult->k5);
        }
        $relevantCategoryIds = array_filter($relevantCategoryIds);
        $relevantCategoryIds = array_unique($relevantCategoryIds);

        $imagesResult = $db->query('
                SELECT
                     b.cPfad
                FROM tartikel a
                JOIN tartikelpict ap ON ap.kArtikel = a.kArtikel
                JOIN tbild b ON b.kBild = ap.kBild
                JOIN tkategorieartikel ka ON ka.kArtikel = a.kArtikel
                WHERE
                    ka.kKategorie IN (' . join(',', $relevantCategoryIds) . ')
                ORDER BY RAND()
                LIMIT 3
        ',
            ReturnType::ARRAY_OF_OBJECTS);


        $images = array();
        foreach ($imagesResult as $imageResult) {
            $image = new Image();
            $image->setCPath($imageResult->cPfad);

            array_push($images, $image);
        }

        return $images;
    }

    /**
     * @param int $categoryId
     * @param string $imageName
     * @param DbInterface $db
     */
    public static function saveCategoryImage(int $categoryId, string $imageName, DbInterface $db)
    {
        $db->insert('tkategoriepict', (object)[
            'cPfad' => $imageName,
            'kKategorie' => $categoryId
        ]);
    }

    public static function removeGeneratedImages(DbInterface $db): void
    {
        $categoryIdsObjects = $db->queryPrepared("
                    SELECT 
                           kp.kKategorie 
                    FROM tkategoriepict kp 
                    WHERE kp.cPfad LIKE :pathPrefix",
            ['pathPrefix' => Bootstrap::CATEGORY_IMAGE_NAME_PREFIX . '%'],
            ReturnType::ARRAY_OF_OBJECTS);

        $categoryIds = \array_map(function ($o) {
            return $o->kKategorie;
        }, $categoryIdsObjects);

        foreach ($categoryIds as $categoryId) {
            $db->delete('tkategoriepict', 'kKategorie', $categoryId);
        }
    }


}