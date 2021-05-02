<?php

namespace Plugin\things4it_category_image_generation\CategoriesHelper;

class Category
{

    private int $kKategorie;

    /**
     * @return int
     */
    public function getKKategorie(): int
    {
        return $this->kKategorie;
    }

    /**
     * @param int $kKategorie
     */
    public function setKKategorie(int $kKategorie): void
    {
        $this->kKategorie = $kKategorie;
    }


}