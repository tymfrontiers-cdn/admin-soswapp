<?php
namespace TymFrontiers;
require_once "../app.init.php";
require_once APP_BASE_INC;

\header("Content-Type: application/json");
\require_login(false);
\check_access("/work-domain", false, "project-admin");

$post = \json_decode( \file_get_contents('php://input'), true); // json data
$post = !empty($post) ? $post : (
  !empty($_POST) ? $_POST : []
);
$gen = new Generic;
$auth = new API\Authentication ($api_sign_patterns);
$http_auth = $auth->validApp ();
if ( !$http_auth && ( empty($post['form']) || empty($post['CSRF_token']) ) ){
  HTTP\Header::unauthorized (false,'', Generic::authErrors ($auth,"Request [Auth-App]: Authetication failed.",'self',true));
}
$rqp = [
  "name"          => ["name","username",3,98,[],'LOWER',['-','.', '_']],
  "acronym"          => ["acronym","username",3,16],
  "task"          => ["task","option",['CREATE','UPDATE']],

  "path" => ["path","text",1,72],
  "icon" => ["icon","text",3,72],

  "description" => ["description","text",15,128],
  "form" => ["form","text",2,72],
  "CSRF_token" => ["CSRF_token","text",5,1024]
];
$req = ['task'];
if (!$http_auth) {
  $req[] = 'form';
  $req[] = 'CSRF_token';
}

if ( \trim($post['task']) == 'CREATE' ) {
  $req[] = "icon";
  $req[] = "path";
  $req[] = "description";
}

$params = $gen->requestParam($rqp,"post",$req);
if (!$params || !empty($gen->errors)) {
  $errors = (new InstanceError($gen,true))->get("requestParam",true);
  echo \json_encode([
    "status" => "3." . \count($errors),
    "errors" => $errors,
    "message" => "Request failed"
  ]);
  exit;
}

if( !$http_auth ){
  if ( !$gen->checkCSRF($params["form"],$params["CSRF_token"]) ) {
    $errors = (new InstanceError($gen,true))->get("checkCSRF",true);
    echo \json_encode([
      "status" => "3." . \count($errors),
      "errors" => $errors,
      "message" => "Request failed."
    ]);
    exit;
  }
}
include PRJ_ROOT . "/src/Pre-Process.php";

$is_new = $params['task'] == 'CREATE';
if ($is_new && (new MultiForm(MYSQL_ADMIN_DB, 'work_domain', 'name'))->findBySql("SELECT * FROM :db:.:tbl: WHERE name='{$params['name']}' OR acronym='{$params['acronym']}' LIMIT 1")) {
  echo \json_encode([
    "status" => "3.1",
    "errors" => ["Domain [name] or [acronym] already exist."],
    "message" => "Request halted."
  ]);
  exit;
}
$domain = $is_new
  ? new MultiForm(MYSQL_ADMIN_DB,'work_domain','name')
  : (new MultiForm(MYSQL_ADMIN_DB,'work_domain','name'))->findById($params['name']);
$domain->name = $params['name'];
if (!empty($params['path'])) $domain->path = $params['path'];
if (!empty($params['acronym'])) $domain->acronym = $params['acronym'];
if (!empty($params['icon'])) $domain->icon = $params['icon'];
if (!empty($params['description'])) $domain->description = $params['description'];
$action = $is_new
  ? $domain->create()
  : $domain->update();
if (!$action) {
  $do_errors = [];

  $domain->mergeErrors();
  $more_errors = (new InstanceError($domain))->get('',true);
  if (!empty($more_errors)) {
    foreach ($more_errors as $method=>$errs) {
      foreach ($errs as $err){
        $do_errors[] = $err;
      }
    }
    echo \json_encode([
      "status" => "4." . \count($do_errors),
      "errors" => $do_errors,
      "message" => "Request incomplete."
    ]);
    exit;
  } else {
    echo \json_encode([
      "status" => "0.1",
      "errors" => [],
      "message" => "Request completed with no changes made."
    ]);
    exit;
  }
}
echo \json_encode([
  "status" => "0.0",
  "errors" => [],
  "message" => "Request was successful!"
]);
exit;
