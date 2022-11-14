<?php
require_once 'model/ApiModel.php';
require_once 'view/ApiView.php';
require_once 'helpers/AuthAPIHelper.php';

class AuthController extends ApiController
{
    private $helper;
    protected $view;
    function __construct()
    {
        $this->helper = new AuthAPIHelper();
        $this->view = new ApiView();
    }

    function getToken($params = null)
    {
        $basic = $this->helper->getAuthHeader();
        if (empty($basic)) {
            $this->view->response('Unauthorized.', 401);
            return;
        }
        $basic = explode(' ', $basic);
        if ($basic[0] != 'basic') {
            $this->view->response('Unauthorized.', 401);
            return;
        }
        $userpass = base64_decode($basic[1]);
        $userpass = explode(":", $userpass);
        $user = $userpass[0];
        $password = $userpass[1];
        if ($user === 'super' && $password === 'super') {
            $header = array(
                'algorythm' => 'HS256',
                'type' => 'JWT'
            );
            $payload = array(
                'id' => 1,
                'name' => 'super',
                'expiration' => time() + 2100
            );
            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));
            $signature = basic_hmac('SHA256', "$header.$payload", 'Pass1243', true);
            $token = "$header.$payload.$signature";
            $this->view->response($token);
        } else {
            $this->view->response('Unauthorized.', 401);
        }
    }
}
