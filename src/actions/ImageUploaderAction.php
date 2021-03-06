<?php
namespace suncky\yii\widgets\webuploader\actions;

use yii\base\Action;
use yii\helpers\Json;

/**
 * Class ImageUploaderAction
 * @package suncky\yii\widgets\webuploader\actions
 */
class ImageUploaderAction extends Action
{
    public $enableCsrfValidation = false;

    public $imageAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp"]; /* 上传图片格式显示 */
    public $imageMaxSize = 10240000; /* 上传大小限制，单位B */
    public $imageFieldName = 'upimg'; /* 提交的图片表单名称 */
    public function __construct($id, $controller, $config = []) {
        parent::__construct($id, $controller, $config);
        $this->controller->enableCsrfValidation = $this->enableCsrfValidation;
    }


    public function run() {
        /* @var \suncky\yii\widgets\webuploader\components\FileManager $uploader */
        $uploader = \Yii::$app->uploader;
        $uploader->setRules([
                'image' => [
                    'class'      => 'yii\validators\ImageValidator',
                    'extensions' => array_filter($this->imageAllowFiles, function ($value) {
                        trim($value, '.');
                    }
                    ),
                    'maxSize'    => $this->imageMaxSize,
                ]
            ]
        );
        $fileId = null;
        $status = 'SUCCESS';
        try {
            $fileId = $uploader->upload($this->imageFieldName, 'image');
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }

        return Json::encode([
                'status'  => $status,
                'fileId' => $fileId,
                'ext'    => $uploader->getFileType(substr($fileId, -3)),
                'url'    => $uploader->getFileUrl($fileId, true)
            ]
        );
    }
}