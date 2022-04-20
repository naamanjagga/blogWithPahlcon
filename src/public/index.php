<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<?php


use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Events\Manager;
use App\Components\Locale;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;



define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$loader = new Loader();

$loader->registerNamespaces(
    [
        'App\Components' => APP_PATH . '/components/',
        'App\Handler' => APP_PATH . '/handler/'
    ]
);
$loader->register();


$di = new FactoryDefault();


include APP_PATH . '/config/router.php';

include APP_PATH . '/config/services.php';

$config = $di->getConfig();

include APP_PATH . '/config/loader.php';

$application = new Application($di);


$bearer = $application->request->get('bearer');

if ($bearer) {
    try {
        $parser = new Parser();
        $tokenObject = $parser->parse($bearer);
        $now        = new \DateTimeImmutable();
        $expires    = $now->getTimestamp();

        $validator = new Validator($tokenObject, 100);
        $validator->validateExpiration($expires);

        $claim = $tokenObject->getClaims()->getPayload();
        $user = $claim['sub'];
    } catch (\Exception $e) {
        echo $e->getMessage();
        die;
    }
    if ($user == 'user') {
        include 'header.php';
    } else {
        include 'adminheader.php';
    }
} else {
    $bearers = $application->request->get('bearers');
    if ($bearers == 'eHR0cHM6XC9cL2xvY2FsaG9zdCJdLCJleHAiOjE2N') {
        include 'header.php';
    } elseif ($bearers == 'eHRkjshiUNkjHOPJHkjNBFHIQOIWHKLJHoiqeiqnikIdCJdLCJleHAiOjE2N') {
        include 'adminheader.php';
    } else {
        include 'head.php';
    }
}

$eventsManager = new Manager();
$eventsManager->attach(
    'application:beforeHandleRequest',
    new \App\Handler\EventHandler()
);

$di->set(
    'EventsManager',
    $eventsManager
);
$di->set('locale', (new Locale())->getTranslator());

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}

include 'footer.php';
