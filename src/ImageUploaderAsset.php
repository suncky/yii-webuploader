<?php
/**
 * 邢帅教育
 *
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 *
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader;

use yii\web\AssetBundle;
use Yii;

class ImageUploaderAsset extends AssetBundle
{

    public $sourcePath = '@vendor/suncky\yii-webuploader/src/assets';
    public $js = [
        'webuploader.js',
        'imageUploader.js',
    ];
    public $css = [
        'css/webuploader.css',
        'css/xs_uploader.css'
    ];

    public function init() {
    	$view = Yii::$app->getView();
    	$url = $view->assetManager->getPublishedUrl(Yii::getAlias($this->sourcePath));
    	$view->registerJs('WEBUPLOADER_HOME_URL="'.$url.'/";', $view::POS_HEAD, 'webuploader');
    
    	parent::init();
    }
}