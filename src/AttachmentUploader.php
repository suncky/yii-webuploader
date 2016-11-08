<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class AttachmentUploader
 * @package suncky\uploader
 * @author Choate <choate.yao@gmail.com>
 */
class AttachmentUploader extends BaseUploader
{
    public $columnClass = '\suncky\yii\widgets\webuploader\AttachmentColumn';

    function registerAssert(array $options) {
        $options['contentOptions'] = ArrayHelper::remove($options['options'], 'contentOptions', []);
        AttachmentUploaderAsset::register($this->getView());
        $this->getView()->registerJs("$('#{$this->options['id']}').xstAttachmentUploader(" . Json::encode($options) . ")");
    }

    public function initColumns(array $options) {
        $contentOptions                      = ArrayHelper::remove($options, 'contentOptions', []);
        $contentOptions['class']             = ArrayHelper::getValue($contentOptions, 'class', 'uploader-name');
        $this->itemOptions['contentOptions'] = $contentOptions;
        $values                              = [];
        if (ArrayHelper::isIndexed($this->value)) {
            foreach ($this->value as $value) {
                $values[] = $this->createColumn($value, $options);
            }
        } else {
            $values[] = $this->createColumn($this->value, $options);
        }
        $this->value = $values;
    }

    protected function createColumn($value, $options) {
        return \Yii::createObject([
                'class' => $this->columnClass, 'contentOptions' => $this->itemOptions['contentOptions'], 'removeOptions' => $this->itemOptions['removeOptions'], 'options' => $options, 'helpOptions' => $this->itemOptions['helpOptions'], 'uploader' => $this,
                'value' => is_array($value) ? $value :Json::decode($value),
            ]
        );
    }
}