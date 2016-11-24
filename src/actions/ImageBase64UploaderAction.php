<?php
namespace suncky\yii\widgets\webuploader\actions;

use xzs\extensions\helpers\ServiceHelper;
use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;

/**
 * 用于post上传base64的图片文件
 * Class ImageBase64UploaderAction
 * @package suncky\yii\widgets\webuploader\actions;
 */
class ImageBase64UploaderAction extends Action
{
    public $enableCsrfValidation = false;

    public $imageAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp"]; /* 上传图片格式显示 */
    public $imageMaxSize = 10240000; /* 上传大小限制，单位B */
    public $imageFieldName = 'upimg'; /* 提交的图片表单名称 */
    public $callback;
    public function __construct($id, $controller, $config = []) {
        parent::__construct($id, $controller, $config);
        $this->controller->enableCsrfValidation = $this->enableCsrfValidation;
    }


    public function run() {
        /* @var \suncky\yii\widgets\webuploader\components\FileManager $uploader */
        $uploader = ServiceHelper::getService('uploader');
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
        if(Yii::$app->user->isGuest){
            throw new ForbiddenHttpException('请登录!');
        }

        if(isset($_POST[$this->imageFieldName])){
            $imageContent = base64_decode($_POST[$this->imageFieldName]);
            /* @var \suncky\yii\widgets\webuploader\components\FileManager $uploader */
            $tempFile = tempnam(sys_get_temp_dir(), 'avatar');
            $fp = fopen($tempFile, 'a');
            fwrite($fp, $imageContent);
            fclose($fp);

            $_FILES[$this->imageFieldName]=[
                'name' =>uniqid().'.jpg',
                'type'=>'image/jpeg',
                'tmp_name'=>$tempFile,
                'error' => 0,
                'size' => filesize($tempFile)
            ];

            try {
                $fileId = $uploader->upload($this->imageFieldName, 'image');
            } catch (\Exception $e) {
                $status = $e->getMessage();
            }
            if($this->callback){
                call_user_func($this->callback,$fileId);
            }
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