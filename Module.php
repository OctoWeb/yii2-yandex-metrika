<?php

namespace octo-web\YandexMetrika;

use octo-web\YandexMetrika\models\YmSettingsParams;
use Curl\Curl;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module {
    
    public $settings;

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    public $controllerNamespace = 'octo-web\YandexMetrika\controllers';
    
    public $OAuthToken = null;
    public $apiUrl = 'http://api-metrika.yandex.ru';
    public $OAuthUrl = 'https://oauth.yandex.ru';


    public function init() {
        parent::init();
        $this->settings = new YmSettingsParams;
    }

    public function callApi($resource, $params = [], $method = self::METHOD_GET) {
        $params['oauth_token']=$this->settings->token;
        $resourceUrl = $this->apiUrl.$resource;
        $curl = new Curl();
        
        $curl->$method($resourceUrl, $params);
        return $curl->response;
    }

}
