<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader\actions;

use yii\base\Action;
use yii\helpers\Json;

/**
 * Class ImageUploaderAction
 * @package jnx\extensions\actions
 * @author Choate <choate.yao@gmail.com>
 */
class AttachmentUploaderAction extends Action
{
    public $enableCsrfValidation = false;

    public $attachmentAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".zip", ".rar", '.tar', '.gz', '.bz2', '.docx', '.doc', '.pdf', '.mp3', '.xls', '.xlsx', '.ppt', '.pptx', '.txt']; /* 上传图片格式显示 */
    public $attachmentMaxSize = 1024000000; /* 上传大小限制，单位B */
    public $attachmentFieldName = 'upatt'; /* 提交的图片表单名称 */
    public $uploadDir = '';
    public function __construct($id, $controller, $config = []) {
        parent::__construct($id, $controller, $config);
        $this->controller->enableCsrfValidation = $this->enableCsrfValidation;
    }


    public function run() {
        /* @var \suncky\yii\widgets\webuploader\components\FileManager $uploader */
        $uploader = \Yii::$app->uploader;
        $uploader->setRules([
                'attachment' => [
                    'class'      => 'yii\validators\FileValidator',
                    'extensions' => array_filter($this->attachmentAllowFiles, function ($value) {
                        trim($value, '.');
                    }
                    ),
                    'maxSize'    => $this->attachmentMaxSize,
                ]
            ]
        );
        if($this->uploadDir) {
            $uploader->setUploadBaseDir($this->uploadDir);
        }
        $fileId = null;
        $status = 'SUCCESS';
        try {
            $fileId = $uploader->upload($this->attachmentFieldName, 'attachment');
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