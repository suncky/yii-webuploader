<?php
namespace suncky\yii\widgets\webuploader;

use yii\web\AssetBundle;
use Yii;

class AttachmentUploaderAsset extends AssetBundle
{

    public $sourcePath = '@vendor/suncky/yii-webuploader/src/assets';
    public $js = [
        'json2.js',
        'webuploader.js',
        'attachmentUploader.js',

    ];
    public $css = [
        'css/webuploader.css',
        'css/xs_uploader.css',
    ];

    public function init() {
    	$view = Yii::$app->getView();
    	$url = $view->assetManager->getPublishedUrl(Yii::getAlias($this->sourcePath));
    	$view->registerJs('WEBUPLOADER_HOME_URL="'.$url.'/";', $view::POS_HEAD, 'webuploader');
    
    	parent::init();
    }
}