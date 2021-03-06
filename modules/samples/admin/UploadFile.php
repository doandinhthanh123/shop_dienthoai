<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2020 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 31 Oct 2020 02:20:33 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['UploadFile'];

//------------------------------
if ($nv_Request->isset_request('submit_upload', 'post') and isset($_FILES, $_FILES['uploadfile'], $_FILES['uploadfile']['tmp_name']) and is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
    // Khởi tạo Class upload
    $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);

    // Thiết lập ngôn ngữ, nếu không có dòng này thì ngôn ngữ trả về toàn tiếng Anh
    $upload->setLanguage($lang_global);

    // Tải file lên server
    $upload_info = $upload->save_file($_FILES['uploadfile'], NV_UPLOADS_REAL_DIR, false, $global_config['nv_auto_resize']);

    echo '<pre><code>';
    echo htmlspecialchars(print_r($upload_info, true));
    die();
}

if ($nv_Request->isset_request('submitremote', 'post')) {
    $remotefile = $nv_Request->get_string('remotefile', 'post', '');
    if (!empty($remotefile)) {
        // Khởi tạo Class upload
        $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);

        // Thiết lập ngôn ngữ, nếu không có dòng này thì ngôn ngữ trả về toàn tiếng Anh
        $upload->setLanguage($lang_global);

        // Lưu file trên internet về server
        $upload_info = $upload->save_urlfile($remotefile, NV_UPLOADS_REAL_DIR, false, $global_config['nv_auto_resize']);

        echo '<pre><code>';
        echo htmlspecialchars(print_r($upload_info, true));
        die();
    }
}
//------------------------------

//------------------------------
// Viết code Sử lý Ảnh
//------------------------------

$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);

// Thay đổi kích thước theo tỉ lệ %
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->resizePercent(100);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.resizePercent.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// resizePercent\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Cắt ảnh từ giữa
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->cropFromCenter(100, 100);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.cropFromCenter.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// cropFromCenter\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Cắt ảnh từ bên trái
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->cropFromLeft(50, 50, 200, 200);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.cropFromLeft.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// cropFromLeft\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Cắt ảnh từ bên trên
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->cropFromTop(200, 200);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.cropFromTop.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// cropFromTop\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Chỉnh kích thước theo hai phương
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->resizeXY(200, 200);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.resizeXY.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// resizeXY\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Tạo bóng đổ
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->reflection();

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.reflection.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// reflection\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Xoay ảnh
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->rotate(-45);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.rotate.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// rotate\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

// Chèn logo vào ảnh
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$config = [
    'w' => 113,
    'h' => 32,
    'x' => 30,
    'y' => 30
];
$image->addlogo(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/images/logo_small.png', 'right', 'bottom', $config);

// Chèn chữ vào ảnh
$image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/nukeviet.png', NV_MAX_WIDTH, NV_MAX_HEIGHT);
$image->addstring('NukeViet CMS', 'right', 'bottom', NV_ROOTDIR . '/includes/fonts/Heineken.ttf', 36);

$image->save(NV_UPLOADS_REAL_DIR, 'nukeviet.addstring.png', 100);
$image->close();
$info = $image->create_Image_info;
$contents .= "// addstring\n" . htmlspecialchars(print_r($info, true)) . "\n\n";

$contents .= '</code></pre>';

//------------------------------
// Viết code Sử lý Ảnh
//------------------------------

$xtpl = new XTemplate('UploadFile.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
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
//-------------------------------

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';