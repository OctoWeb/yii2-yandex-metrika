<?php
namespace octo-web\YandexMetrika\models;

use Yii;
use yii\base\Model;

class YmSettingsParams extends Model{
    
    public $appId;
    public $appPassword;
    public $metrikId;
    public $token;

    public function rules(){
        return [
            [['appId','appPassword','metrikId'], 'required'],
            [['appId','appPassword','metrikId','token'], 'string', 'max' => 255]
        ];
    }
    public function attributeLabels(){
        return [
            'appId' => 'ID приложения',
            'appPassword'=>'Пароль',
            'metrikId'=>'ID метрики',
            'token' => 'Токен',
        ];
    }
    
    public function init(){
        $settings = YmSettings::findOne(['id' => 1]);
        $this->appId = $settings->token;
        
        $settings = YmSettings::findOne(['id' => 2]);
        $this->appPassword = $settings->token;
        
        $settings = YmSettings::findOne(['id' => 3]);
        $this->metrikId = $settings->token;
        
        $settings = YmSettings::findOne(['id' => 4]);
        $this->token = $settings->token;
    }
    
    public function save(){
        $settings = YmSettings::findOne(['id' => 1]);
        $settings->token=$this->appId;
        $settings->save();
        
        $settings = YmSettings::findOne(['id' => 2]);
        $settings->token=$this->appPassword;
        $settings->save();
        
        $settings = YmSettings::findOne(['id' => 3]);
        $settings->token=$this->metrikId;
        $settings->save();
        
        $settings = YmSettings::findOne(['id' => 4]);
        $settings->token=$this->token;
        $settings->save();
    }
}
