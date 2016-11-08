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
 * Class ImageUploader
 * @package uploader
 * @author Choate <choate.yao@gmail.com>
 */
class ImageUploader extends BaseUploader
{
    public $columnClass = '\suncky\yii\widgets\webuploader\ImageColumn';

    function registerAssert(array $options) {
        $options['imgOptions']    = ArrayHelper::remove($options['options'], 'imgOptions', []);
        ImageUploaderAsset::register($this->getView());
        $this->getView()->registerJs("$('#{$this->options['id']}').xstImageUploader(" . Json::encode($options) . ")");
    }

    public function initColumns(array $options) {
        $imgOptions                         = ArrayHelper::remove($options, 'imgOptions', []);
        $imgOptions['width']                = ArrayHelper::getValue($imgOptions, 'width', 110);
        $imgOptions['height']               = ArrayHelper::getValue($imgOptions, 'height', 110);
        $this->itemOptions['imgOptions']    = $imgOptions;
        foreach ($this->value as $index => $value) {
            $column              = \Yii::createObject([
                    'class' => $this->columnClass, 'imgOptions' => $imgOptions, 'removeOptions' => $this->itemOptions['removeOptions'], 'options' => $options, 'helpOptions' => $this->itemOptions['helpOptions'], 'uploader' => $this,
                    'value' => $value,
                ]
            );
            $this->value[$index] = $column;
        }
    }
}