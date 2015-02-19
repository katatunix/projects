<?php

error_reporting(E_ALL);

include 'gereport/common.php';

__import('controller/Toolbox');
__import('database/MySqlDatabase');
__import('session/Session');
__import('request/Request');
__import('handler/RootHandler');

use gereport\controller\Toolbox;
use gereport\database\MockDatabase;
use gereport\database\MySqlDatabase;
use gereport\session\Session;
use gereport\request\Request;
use gereport\handler\RootHandler;

session_start();

$toolbox = new Toolbox();

$toolbox->database = new MySqlDatabase('localhost', 'root', '', 'gereport');
$toolbox->session = new Session('gereport_session_key');
$toolbox->request = new Request($_SERVER['REQUEST_METHOD'] == 'POST', $_POST, $_GET);
$toolbox->htmlDir = __ROOT_DIR . '../html/';

$rootHandler = new RootHandler(__ROOT_URL, $toolbox);

$toolbox->redirector = $rootHandler;
$toolbox->urlSource = $rootHandler;

$rootHandler->handle();

$toolbox->database->disconnect();
