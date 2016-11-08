<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader\components;

use Yii;
use Intervention\Image\ImageManager;
/**
 * Class Image
 * @package suncky\uploader\components;
 * @author Choate <choate.yao@gmail.com>
 */
class Image extends BaseImage
{
    /**
     * @var \Intervention\Image\Image
     *
     * @author Choate <choate.yao@gmail.com>
     */
    private $_imageManager;

    public function save() {
        $this->getOperation() == 2 && $this->getImageManager()->rotate($this->getRotate());
        $this->getImageManager()->resize($this->getWidth(), $this->getHeight(), function($constraint) {
            if (!$this->getWidth() || !$this->getHeight()) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        });
        $this->getOperation() == 1 && $this->getImageManager()->rotate($this->getRotate());
        $this->getImageManager()->save($this->getTempPath() . $this->getFile()->getName());
        $this->getImageManager()->destroy();

        return $this->getStorage()->getFileUrl($this->getStorage()->save(new File(['pathname' => $this->getTempPath() . $this->getFile()->getName()])));
    }
    
    /**
     * @author Hollis_Ho
     * @see \suncky\uploader\components\BaseImage::saveImg()
     */
    public function saveImg() {
        if($this->getCrop()){
            //进行裁剪
            $this->getImageManager()->crop($this->getWidth(), $this->getHeight(), $this->getCropX(), $this->getCropY());
        }else {
            //不裁剪，只做缩放
            $this->getOperation() == 2 && $this->getImageManager()->rotate($this->getRotate());
            $this->getImageManager()->resize($this->getWidth(), $this->getHeight(), function($constraint) {
                if (!$this->getWidth() || !$this->getHeight()) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            });
            $this->getOperation() == 1 && $this->getImageManager()->rotate($this->getRotate());
        }
        $this->getImageManager()->save($this->getTempPath() . $this->getFile()->getName());
        $this->getImageManager()->destroy();
        return $this->getStorage()->getFileUrl($this->getStorage()->save(new File(['pathname' => $this->getTempPath() . $this->getFile()->getName()])));
    }

    public function getWidth() {
        if ($this->isScalingByWidth() && parent::getHeight()) {
            return $this->getImageManager()->getWidth() / ($this->getImageManager()->getHeight() / parent::getHeight());
        }

        return parent::getWidth();
    }

    public function getHeight() {
        if ($this->isScalingByHeight() && parent::getWidth()) {
            return $this->getImageManager()->getHeight() / ($this->getImageManager()->getWidth() / parent::getWidth());
        }

        return parent::getHeight();
    }

    public function setFile(File $file) {
        parent::setFile($file);
        $this->_imageManager = (new ImageManager(['driver' => $this->getDriver()]))->make($this->getFile()->getPathname());
    }
    
    /**
     * @author Hollis_Ho
     * @see \suncky\uploader\components\BaseImage::setImgUrl()
     */
    public function setImgUrl($img_url) {
        parent::setImgUrl($img_url);
        $this->_imageManager = (new ImageManager(['driver' => $this->getDriver()]))->make($img_url);
    }

    /**
     * ImageManager
     * @author Choate <choate.yao@gmail.com>
     * @return \Intervention\Image\Image
     */
    public function getImageManager() {
        return $this->_imageManager;
    }

    /**
     * 是否缩放宽度
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     * @return bool
     */
    protected function isScalingByWidth() {
        return !parent::getWidth() || (!($this->getLarge() && parent::getWidth() > $this->getImageManager()->getWidth()) &&
                                       (($this->getImageManager()->getWidth() > $this->getImageManager()->getHeight() && $this->getPrefer() == 0) ||
                                        ($this->getImageManager()->getWidth() < $this->getImageManager()->getHeight() && $this->getPrefer() == 1)));
    }

    /**
     * 是否缩放高度
     *
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     * @return bool
     */
    protected function isScalingByHeight() {
        return !parent::getHeight() || (!($this->getLarge() && parent::getHeight() > $this->getImageManager()->getHeight()) &&
                                        (($this->getImageManager()->getHeight() > $this->getImageManager()->getWidth() && $this->getPrefer() == 0) ||
                                         ($this->getImageManager()->getHeight() < $this->getImageManager()->getWidth() && $this->getPrefer() == 1)));
    }
}