<?php declare(strict_types=1);

use JTL\Catalog\Category\Kategorie;
use JTL\DB\ReturnType;
use JTL\Helpers\Category as CategoryHelper;
use JTL\Helpers\Form;

if (!defined('PFAD_ROOT')) {
    require_once __DIR__ . '/../../../admin/includes/admininclude.php';
}
global $plugin;

\header('Content-Type: application/json');

if (Form::validateToken()) {
    $db = Shop::Container()->getDB();
    $categoryHelper = CategoryHelper::getInstance();

    $categoryName = \JTL\Helpers\Request::postVar('categoryName');
    $categoryResults = $db->queryPrepared('SELECT kKategorie FROM tkategorie k WHERE k.cName LIKE (:search)',
        ['search' => '%' . $categoryName . '%'],
        ReturnType::ARRAY_OF_OBJECTS);

    $data = array();
    foreach ($categoryResults as $categoryResult) {
        $category = new Kategorie((int)$categoryResult->kKategorie);
        $data[$category->getID()] = $categoryHelper->getPath($category);
    }

    \http_response_code(200);
    echo \json_encode($data);
} else {
    \http_response_code(403);

    $error = [
        'message' => __('admin.regenerate.common.csrf-error.message')
    ];

    echo \json_encode($error);
}


