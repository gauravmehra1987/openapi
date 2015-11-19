<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


$route['_/js'] = "common/script/js";
$route['contact-us'] = "pages/contact";
$route['success'] = "common/success";
$route['search'] = "catalog/product/search";
$route['search/(:any)'] = "catalog/product/search/$1";
$route['ajax/pro'] = "catalog/product/ajax";
$route['ajax/cmp'] = "catalog/compare/ajax";
$route['ajax/cls'] = "catalog/category/ajax";
$route['auth/login'] = "account/customer/login";
$route['auth/logout'] = "account/customer/logout";
$route['auth/fbconnect'] = "account/customer/fbconnect";
$route['compare/(:any)'] = "catalog/compare/index/$1";

$request = $this->uri->_detect_uri();



$slug = end(explode('/', $request));

if($request){
    if(preg_match('/sitemap/', $request)){ 
    $route['(:any)'] = "tool/sitemap/index/$1";
    }elseif(preg_match('/-vs-/', $request) && !preg_match('/compare/', $request)){ 
        $route['(:any)'] = "catalog/compare/products/$1";
    }else{
        $routes = findRoutes($slug);
        foreach($routes as $row ){
            $action=explode('=', $row->query);
            switch($action[0]){
                case 'category_id':
                    $route['(:any)/(:any)'] = "catalog/category/view/$1/$2";
                    $route['(:any)'] = "catalog/category/detail/$1";
                    break;
                case 'product_id':
                    $route['(:any)/(:any)'] = "catalog/product/view/$2";
                    $route['(:any)'] = "catalog/product/view/$1";
                    break;
                case 'information_id':
                    echo "This is a info page";
                    break;

            }
        }
    }
}
 
$route['default_controller'] = "common/home";
$route['404_override'] = '';


function findRoutes($key){ 
    $data = array();
    require_once 'database.php';
    $conn = mysql_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password']);
    $db = mysql_select_db($db['default']['database'],$conn);
    $query = mysql_query("select * from oc_url_alias where keyword = '$key'");
    echo mysql_error();
    if(mysql_num_rows($query)){
        while($row = mysql_fetch_object($query)){
            $data[] = $row;
        }
    }
    mysql_close($conn);

    return $data;
}


/* End of file routes.php */
/* Location: ./application/config/routes.php */