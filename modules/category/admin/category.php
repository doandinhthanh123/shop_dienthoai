<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2020 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 31 Oct 2020 02:20:33 GMT
 */
include "connect.php";
if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['category'];

//------------------------------
// Viết code xử lý chung vào đây 

// ========= Xóa dữ liệu ========== //

if ($nv_Request->isset_request("action", "post,get")) {
    $id_delete = $nv_Request->get_int('id', 'post,get', 0);
    $checksess = $nv_Request->get_title('checksess', 'post,get', 0);
    if ($id_delete > 0 and $checksess == md5($id_delete . NV_CHECK_SESSION)) {
        $db->query("DELETE FROM `shop_category` WHERE id=" . $id_delete);
    }
}
// =============================== //

// ============== Phân trang Dữ Liệu ================= //

$perpage = 9;
$page = $nv_Request->get_int('page', 'get', 1);


$db->sqlreset()
    ->select('COUNT(*)')
    ->from('shop_category');
$sql = $db->sql();
$total = $db->query($sql)->fetchColumn();

$db->select('*')
    ->order("weight ASC")
    ->limit($perpage)
    ->offset(($page - 1) * $perpage);
$sql = $db->sql();

$result = $db->query($sql);


while ($row = $result->fetch()) {
    $array_row[$row['id']] = $row;
}

// =========================================== //
// =========== Thay đổi Số thứ tự  ============= //
// =========================================== //
if ($nv_Request->isset_request("change_weight", "post,get")) {
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post,get', 0);

    if ($id > 0 and $new_weight > 0) {
        $sql = "SELECT id,weight FROM shop_category WHERE id != " . $id;
        $result = $db->query($sql);
        $weight = 0;
        while ($category = $result->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $exe = $db->query("UPDATE `shop_category` SET weight=" . $weight . " WHERE id=" . $category['id']);
        }
        $exe = $db->query("UPDATE `shop_category` SET weight=" . $new_weight . " WHERE id=" . $id);
    }
    if ($exe) {
        die("OK!");
    }
    die("Error");
}
// =========================================== //
// =========== Thay đổi Số thứ tự  ============= //
// =========================================== //





//------------------------------

$xtpl = new XTemplate('category.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

//-------------------------------
// Viết code xuất ra site vào đây

$i = ($page - 1) * $perpage;

foreach ($array_row as $category) {
    $category['stt'] = $i;
    $category['stt'] = $i + 1;

    for ($j = 1; $j <= $total; $j++) {
        $xtpl->assign('J', $j);
        $xtpl->assign('J_SELECT', $j == $category['weight'] ? 'selected="selected"' : '');

        $xtpl->parse('main.category.stt');
    }
    // Sử dụng assign, gán giá trị $array cho DATA
    $category['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE .
        '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=create_category&amp;id=' . $category['id'];
    $category['url_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE .
        '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=category&amp;id=' . $category['id'] . '&action=delete&checksess=' . md5($category['id'] . NV_CHECK_SESSION);
    $xtpl->assign('CG', $category);
    $xtpl->parse('main.category');
    $i++;
}

// ========vPhân Trang ======== //

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE .
    '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=category';
$generate_page = nv_generate_page($base_url, $total, $perpage, $page);


//Nếu số bản ghi > 5 thì hiển thị khối phân trang
if ($total > 5) {
    $xtpl->assign('GP', $generate_page);
}
/* end code xuất ra site */



//-------------------------------




$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';