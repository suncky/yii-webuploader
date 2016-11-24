<?php
namespace suncky\yii\widgets\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ImageColumn
 * @package suncky\yii\widgets\webuploader
 */
class ImageColumn extends BaseColumn
{
    public $imgOptions = [];

    public function renderDataCell() {
        $tag     = ArrayHelper::remove($this->options, 'tag', 'li');
        $content = $this->createHidden() . Html::img($this->getImageUrl(), $this->imgOptions) . Html::tag('div', '', $this->helpOptions) . Html::a(ArrayHelper::remove($this->removeOptions, 'label', '删除'), 'javascript:;', $this->removeOptions);

        return Html::tag($tag, $content, $this->options);
    }

    protected function getImageUrl() {
        /* @var \suncky\yii\widgets\webuploader\components\FileManager $uploader */
        $uploader = \Yii::$app->uploader;
        /* @var \suncky\yii\widgets\webuploader\components\BaseImage $imageManager */
        $imageManager = \Yii::$app->imageManager;
        $file         = $uploader->getStorage()->getFile($this->value);
        if ($file) {
            $imageManager->setFile($file);
            $imageManager->setWidth(ArrayHelper::getValue($this->imgOptions, 'width', 110));
            $imageManager->setHeight(ArrayHelper::getValue($this->imgOptions, 'height', 110));
            $imageManager->setPrefer(2);

            return $imageManager->save();
        }

        return $uploader->getFileUrl($this->value);
    }
}