<?php declare(strict_types=1);

use JTL\DB\ReturnType;
use JTL\Helpers\Form;

if (!defined('PFAD_ROOT')) {
    require_once __DIR__ . '/../../../admin/includes/admininclude.php';
}
global $plugin;

\header('Content-Type: application/json');

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

    \http_response_code(200);
    echo \json_encode($data);
} else {
    \http_response_code(403);

    $error = [
        'message' => __('admin.regenerate.common.csrf-error.message')
    ];

    echo \json_encode($error);
}


