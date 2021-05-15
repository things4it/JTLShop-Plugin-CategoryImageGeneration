<?php

namespace Plugin\t4it_category_image_generation\src\db\entity;

class CategoryCronJobEntry
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