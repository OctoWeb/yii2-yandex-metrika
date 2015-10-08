<?
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Настройк модуля';
$this->params['breadcrumbs'][] = ['label' => 'Yandex метрика', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;?>
<p>
Чтоб дать доступ к метрики сайта, вы должны создать под учетной записью Яндекса, под той же что вы создавали метрику, новое приложение <a target="_blank" href="https://oauth.yandex.ru/">https://oauth.yandex.ru/</a>
</p>
<div class="row">
    <div class="col-sm-6">
        <label>Ссылка на сайт приложения:</label>
        <p><?=Yii::$app->UrlManager->hostInfo.Url::to(['index'])?></p>
    </div>
    <div class="col-sm-6">
        <label>Callback URL:</label>
        <p><?=Yii::$app->UrlManager->hostInfo.Url::to(['verification-code'])?></p>
    </div>
    
    <?$form = ActiveForm::begin();?>
        <div class="col-xs-12">
            <?$tempate='{label}{input}{hint}{error}';
            if(!empty($model->appId) && !empty($model->appPassword) && !empty($model->metrikId))
                $tempate='{label}<div class="input-group">{input}<span class="input-group-btn"><a href="'.Url::to(['auth']).'" class="btn btn-default">Получить токен</a></span></div>{hint}{error}';
            echo $form->field($model, 'token',['template'=>$tempate])->textInput(['class'=>'form-control','disabled'=>'disabled']) ?>
        </div>
        
        <div class="col-sm-6">        
            <?= $form->field($model, 'appId')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'appPassword')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'metrikId')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6 text-right">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
    
    
    <?php ActiveForm::end(); ?>
</div>