<?php declare(strict_types=1);

use JTL\DB\ReturnType;
use JTL\Helpers\Form;

if (!defined('PFAD_ROOT')) {
    require_once __DIR__ . '/../../../admin/includes/admininclude.php';
}
global $plugin;

if (Form::validateToken()) {
    $db = Shop::Container()->getDB();
    $categoryName = \JTL\Helpers\Request::postVar('categoryName');
    $categories = $db->queryPrepared('SELECT * FROM tkategorie k WHERE k.cName LIKE (:search)',
        ['search' => '%' . $categoryName . '%'],
        ReturnType::ARRAY_OF_OBJECTS);
    $data = array();
    foreach ($categories as $category) {
        $data[$category->kKategorie] = $category->cName;
    }
} else {
    // TODO
}

\header('Content-Type: application/json');
echo \json_encode($data);