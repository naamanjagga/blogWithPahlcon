<?php

namespace App\Handler;

use Phalcon\Mvc\Application;
use Phalcon\Mvc\Controller;
use Phalcon\Events\Event;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Phalcon\Mvc\Dispatcher;

class EventHandler extends Controller
{
    public function createToken($role)
    {
        $signer  = new Hmac();

        $builder = new Builder($signer);

        $now        = new \DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        $builder
            ->setAudience('https://localhost')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp 
            ->setId('abcd123456789')                    // JTI id 
            ->setIssuedAt($issued)                      // iat 
            ->setIssuer('https://phalcon.io')           // iss 
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject($role)   // sub
            ->setPassphrase($passphrase)                // password 
        ;

        $tokenObject = $builder->getToken();

         return $tokenObject->getToken();
    }
    public function beforeHandleRequest(EVENT $event, Application $application, Dispatcher $containerspatcher)
    {
        $aclFile = APP_PATH . '/security/acl.cache';
        if (true === is_file($aclFile)) {
            $acl = unserialize(
                file_get_contents($aclFile)
            );

            $bearer = $application->request->get('bearer');
            if ($bearer) {
                try {
                    $parser = new Parser();
                    $tokenObject = $parser->parse($bearer);
                    $now        = new \DateTimeImmutable();
                    $expires    = $now->getTimestamp();
                    // $expires    = $now->modify('+1 day')->getTimestamp();

                    $validator = new Validator($tokenObject, 100);
                    $validator->validateExpiration($expires);
                    // echo 'validate';
                    // die;

                    $claim = $tokenObject->getClaims()->getPayload();
                    $user = $claim['sub'];
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    die;
                }
                $controller = $containerspatcher->getControllerName();
                $action     = $containerspatcher->getActionName();
                if (true !== $acl->isAllowed($user, $controller, $action)) {
                    echo 'Access denied';
                    die();
                } else {
                }
            }
        } else {
            // echo 'file not found';
            // die;
        }
    }
}