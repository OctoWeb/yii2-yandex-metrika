<?php

namespace octoweb\YandexMetrika\controllers;

use Yii;
use backend\components\MyController as Controller;
use octoweb\YandexMetrika\models\YmSettingsParams;
use yii\data\ArrayDataProvider;
use Curl\Curl;

class DefaultController extends Controller {

    public function beforeAction($action) {
        $request = Yii::$app->getRequest();
        if (
            empty($this->module->settings->token)
            && strpos($request->getUrl(),'settings') === false
            && strpos($request->getUrl(),'auth') === false
            && strpos($request->getUrl(),'verification-code') === false
        ) {
            $this->redirect(['settings']);
        }
        return parent::beforeAction($action);
    }
    public function actionAuth() {
        $this->redirect($this->module->OAuthUrl . '/authorize?response_type=code&client_id=' . $this->module->settings->appId);
    }
    public function actionVerificationCode() {
        $code = Yii::$app->request->get('code');
        $curl = new Curl();
        $curl->post($this->module->OAuthUrl . '/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->module->settings->appId,
            'client_secret' => $this->module->settings->appPassword,
        ]);
        if($curl->error==1){
            Yii::$app->session->setFlash('error',$curl->errorMessage.'<br/>Не верный "ID приложения" или "Пароль"');
            $this->redirect(['settings']);
        }else{
            $token = $curl->response->access_token;
            $settings = new YmSettingsParams;
            $settings->token = $token;
            $settings->save();
            $this->redirect(['index']);
        }
    }
    
    public function actionSettings(){
        $model=$this->module->settings;
        if ($model->load(Yii::$app->request->post())){
            $model->save();
        }
        return $this->render('settings', ['model' => $model]);
    }

    public function actionIndex(){
        $response=$this->module->callApi('/stat/traffic/summary',[
                'id' => $this->module->settings->metrikId,
                'date1'=>(isset($_GET['date1']))?$_GET['date1']:date('Ym01',time()),
                'date2'=>(isset($_GET['date2']))?$_GET['date2']:date('Ymd',time()),
                'offset'=>(isset($_GET['offset']))?$_GET['offset']:1,
                'per_page'=>(isset($_GET['per_page']))?$_GET['per_page']:100
            ]);
        
        
        if(get_class($response)=='SimpleXMLElement'){
            if(isset($response->errors))
                Yii::$app->session->setFlash('error',$response->errors->error->code.'<br/>Не верен "ID метрики"');
            else{
                $returt=[];
                $returt['date1']=$response->date1;
                $returt['date2']=$response->date2;
                $returt['totals']=(array)$response->totals;
                $count=$response->data->attributes()->count;
                if($count>1){
                    for($i=0;$i<$count;$i++){
                        $returt['row'][]=(array)$response->data->row[$i];
                    }
                    
                }else
                    $returt['row'][]=(array)$response->data->row;
                return $this->render('index', ['returt' => (object)$returt]);
            }
        }else
            Yii::$app->session->setFlash('error',$response->code.'<br/>Устарел или не верен "Токен"');
        
        $this->redirect(['settings']);
    }
    
}
