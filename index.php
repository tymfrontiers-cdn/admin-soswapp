<?php
namespace TymFrontiers;
require_once "app.init.php";
require_once APP_BASE_INC;
require_once APP_ROOT . "/src/Helper.php";
HTTP\Header::redirect(WHOST . "/admin/dashboard");
