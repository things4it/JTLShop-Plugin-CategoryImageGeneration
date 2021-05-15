<?php

namespace Plugin\t4it_category_image_generation\src\db\entity;

class Image
{

    private string $cPath;

    /**
     * @return string
     */
    public function getCPath(): string
    {
        return $this->cPath;
    }

    /**
     * @param string $cPath
     */
    public function setCPath(string $cPath): void
    {
        $this->cPath = $cPath;
    }


}