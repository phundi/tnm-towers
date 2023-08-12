<?php 
require_once realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';
include 'admin-panel/function.php';
// if ( $config->maintenance_mode == 1 ) {
// 	$maintenance_contoller_file       = $_CONTROLLERS . 'maintenance.php';
//     if (file_exists($maintenance_contoller_file)) {
//         require_once $maintenance_contoller_file;
//         Maintenance::show();
//         exit();
//     }
// }

if ($is_admin == false && auth()->admin != 2) {
	header('Location: ' . $config->uri);
    exit();
}
$default_logo = $_BASEPATH . 'themes' . $_DS . $config->theme  . $_DS . 'assets' . $_DS . 'img' . $_DS . 'logo.png';
$light_logo = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS . 'assets' . $_DS . 'img' . $_DS . 'logo-light.png';
$wo['config']['sitelogo'] = $config->uri . '/themes/' . $config->theme . '/assets/img/logo.png';
$wo['config']['siteIcon'] = $config->uri . '/themes/' . $config->theme . '/assets/img/icon.png';
if( file_exists($light_logo) ){
    if( $config->displaymode == 'night' ) {
        $wo['config']['sitelogo'] = $config->uri . '/themes/' . $config->theme . '/assets/img/logo-light.png';
    }
}
$path = (!empty($_GET['path'])) ? getPageFromPath($_GET['path']) : null;
$files = scandir('admin-panel/pages');
unset($files[0]);
unset($files[1]);
unset($files[2]);
$page = 'dashboard';
if (!empty($path['page']) && in_array($path['page'], $files) && file_exists('admin-panel/pages/'.$path['page'].'/content.phtml')) {
    $page = $path['page'];
}
$data = array();
$text = Wo_LoadAdminPage($page.'/content');
?>
<input type="hidden" id="json-data" value='<?php echo htmlspecialchars(json_encode($data));?>'>
<?php
echo $text;
?>