<?php

namespace Plugin\t4it_category_image_generation\src\db\entity;

class Category
{

    /**
     * @var int
     */
    private $kKategorie;

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