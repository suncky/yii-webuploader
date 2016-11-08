<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader;

use yii\base\Object;
use yii\helpers\Html;

/**
 * Class BaseColumn
 * @package suncky\uploader
 * @author Choate <choate.yao@gmail.com>
 */
abstract class BaseColumn extends Object
{
    public $options = [];
    public $removeOptions = [];
    public $helpOptions = [];
    public $value;
    /**
     * @var BaseUploader
     * @author Choate <choate.yao@gmail.com>
     */
    public $uploader;

    abstract public function renderDataCell();

    protected function createHidden() {
        return $this->uploader->hasModel() ?
            Html::activeHiddenInput($this->uploader->model, $this->uploader->attribute, ['value' => $this->value, 'name' => Html::getInputName($this->uploader->model, $this->uploader->attribute) . '[]'])
            : Html::hiddenInput($this->uploader->name . '[]', $this->value);
    }
}