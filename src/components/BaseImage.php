<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader\components;

use yii\base\Object;

/**
 * Class BaseImage
 * @package sjy\upload\components
 * @author Choate <choate.yao@gmail.com>
 */
abstract class BaseImage extends Object
{
    /**
     * @var BaseStorage
     *
     * @author Choate <choate.yao@gmail.com>
     */
    private $_storage;

    /**
     * @var File
     *
     * @author Choate <choate.yao@gmail.com>
     */
    private $_file;
    
    /**
     * 图片绝对路径
     * 
     * @var
     * 
     * @author Hollis_Ho
     */
    private $_img_url;
    
    /**
     * 是否裁剪图片 0=否； 1=是
     * 
     * @var
     * 
     * @author Hollis_Ho
     */
    private $_crop = 0;
    
    /**
     * 裁剪开始的x坐标
     * 
     * @var
     * 
     * @author Hollis_Ho
     */
    private $_crop_x = 0;
    
    /**
     * 裁剪开始的y坐标
     *
     * @var
     *
     * @author Hollis_Ho
     */
    private $_crop_y = 0;

    /**
     * 图片处理驱动
     *
     * @var string
     *
     * @author Choate <choate.yao@gmail.com>
     */
    private $_driver = 'gd';

    /**
     * 缩略图宽度
     * @var
     */
    private $_width = 0;

    /**
     * 缩略图高度
     * @var
     */
    private $_height = 0;

    /**
     * 按比例缩放, 倍数百分比, 小于100为所需, 等于100不变, 取值范围 1~1000(10倍)
     * 如果和 w, h 一起使用, p讲直接作用于 w, h(百分之 * P) 得到新的 w, h
     * 如 100w_100h_200p 的作用跟 200w_200h 的效果是一样的
     * 如果对图片进行倍数放大, 单边的最大长度不能超过 4096 * 4
     * @var
     */
    private $_proportion;

    /**
     * 对缩略图旋转, 取值范围 0~360, 默认 0 不旋转.
     * @var int
     */
    private $_rotate = 0;

    /**
     * 进行自动旋转:
     * 0：表示按原图默认方向，不进行自动旋转
     * 1：表示根据图片的旋转参数，对图片进行自动旋转，如果存在缩略参数，是先进行缩略，再进行旋转。
     * 2: 表示根据图片的旋转参数，对图片进行自动旋转，如果存在缩略参数，先进行旋转，再进行缩略
     *
     * 如果采用缩略旋转1,可能会导致图片最终的宽度和高度跟指定的参数不符。
     * 进行自适应方向旋转，必须要求原图的宽度和高度必须小于4096.
     * 如果原图是没有旋转参数，加上1o, 2o参数不会对图有影响。
     * @var
     */
    private $_operation;

    /**
     * 等比缩放优先边: 0默认长边, 1短边, 2强制(不等比)
     * 由于图片缩放过程中, 原图尺寸与缩放尺寸不一定是相同比例, 需要我们指定以长边还是短边优先进行缩放,
     * 如原图 200 * 400(比例1:2), 需要缩放为 100 * 100(比例1:1).
     * 长边优先时, 缩放为50 100；短边优先时(e=1), 缩放为`100 200`, 若不特别指定, 则代表长边优先.
     * @var int
     */
    private $_prefer = 0;

    /**
     * 缩略图大于原图是否处理: 0默认处理, 1不处理; 会减弱图片质量
     * @var int
     */
    private $_large = 0;

    /**
     * 锐化缩略图, 取值范围: 50~399, 越大越清晰; 推荐值 100;
     * 默认不锐化
     * example: example.com/example.jpg@100sh
     * @var int
     */
    private $_sharpen = 0;

    /**
     * 模糊效果, 参数格式: radius-sigma; radius取值范围: 1~50, 越大越模糊;  sigma取值范围1~50, 越大越模糊;
     * 默认不模糊
     * example: example.com/example.jpg@3-2bl
     * @var
     */
    private $_blur;

    /**
     * 质量转换
     *
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     */
    private $_quality;

    /**
     * 绝对质量转换
     *
     * @var bool
     *
     * @author Choate <choate.yao@gmail.com>
     */
    private $_absoluteQuality = false;

    /**
     * 格式转换
     *
     * @author Choate <choate.yao@gmail.com>
     */
    private $_format;
    
    /**
     * return int
     * 
     * @author Hollis_Ho
     */
    public function getCrop() {
        return $this->_crop;
    }
    
    /**
     * @param $crop
     * 
     * @author Hollis_Ho
     */
    public function setCrop($crop) {
        $this->_crop = $crop;
    }
    
    /**
     * @return int
     * 
     * @author Hollis_Ho
     */
    public function getCropX() {
        return $this->_crop_x;
    }
    
    /**
     * @param int $crop_x
     * 
     * @author Hollis_Ho
     */
    public function setCropX($crop_x) {
        $this->_crop_x = $crop_x;
    }
    
    /**
     * @return int
     * 
     * @author Hollis_Ho
     */
    public function getCropY() {
        return $this->_crop_y;
    }
    
    /**
     * @param int $crop_y
     * 
     * @author Hollis_Ho
     */
    public function setCropY($crop_y) {
        $this->_crop_y = $crop_y;
    }
    
    /**
     * 获取图片来源（图片绝对路径）
     * @author Hollis_Ho
     * @return url
     */
    public function getImgUrl() {
        return $this->_img_url;
    }
    
    /**
     * 设置图片来源（图片绝对路径）
     * @author Hollis_Ho
     * @param $img_url
     */
    public function setImgUrl($img_url) {
        $this->_img_url = $img_url;
    }

    /**
     * File
     * @author Choate <choate.yao@gmail.com>
     * @return File
     */
    public function getFile() {
        return $this->_file;
    }

    /**
     * @param File $file
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setFile(File $file) {
        $this->_file = $file;
    }

    /**
     * Width
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getWidth() {
        return $this->_width;
    }

    /**
     * @param mixed $width
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setWidth($width) {
        $this->_width = $width < 0 ? 0 : ($width > 4096 ? 4096 : $width);
    }

    /**
     * Height
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getHeight() {
        return $this->_height;
    }

    /**
     * @param mixed $height
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setHeight($height) {
        $this->_height = $height < 0 ? 0 : ($height > 4096 ? 4096 : $height);
    }

    /**
     * Proportion
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getProportion() {
        return $this->_proportion;
    }

    /**
     * @param mixed $proportion
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setProportion($proportion) {
        $this->_proportion = $proportion < 0 ? 0 : ($proportion > 1000 ? 1000 : $proportion);
    }

    /**
     * Rotate
     * @author Choate <choate.yao@gmail.com>
     * @return int
     */
    public function getRotate() {
        return $this->_rotate;
    }

    /**
     * @param int $rotate
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setRotate($rotate) {
        $this->_rotate = $rotate < 0 ? 0 : ($rotate > 360 ? 360 : $rotate);
    }

    /**
     * Operation
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getOperation() {
        return $this->_operation;
    }

    /**
     * @param mixed $operation
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setOperation($operation) {
        $this->_operation = in_array($operation, [0, 1, 2]) ? $operation : 0;
    }

    /**
     * Prefer
     * @author Choate <choate.yao@gmail.com>
     * @return int
     */
    public function getPrefer() {
        return $this->_prefer;
    }

    /**
     * @param int $prefer
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setPrefer($prefer) {
        $this->_prefer = in_array($prefer, [0, 1, 2]) ? $prefer : 1;
    }

    /**
     * Large
     * @author Choate <choate.yao@gmail.com>
     * @return int
     */
    public function getLarge() {
        return $this->_large;
    }

    /**
     * @param int $large
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setLarge($large) {
        $this->_large = in_array($large, [0, 1]) ? $large : 0;;
    }

    /**
     * Sharpen
     * @author Choate <choate.yao@gmail.com>
     * @return int
     */
    public function getSharpen() {
        return $this->_sharpen;
    }

    /**
     * @param int $sharpen
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setSharpen($sharpen) {
        $this->_sharpen = $sharpen < 50 ? 50 : ($sharpen > 399 ? 399 : $sharpen);;
    }

    /**
     * Blur
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getBlur() {
        return $this->_blur;
    }

    /**
     * @param mixed $blur
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setBlur($blur) {
        $this->_blur = $blur;
    }

    /**
     * Quality
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getQuality() {
        return $this->_quality;
    }

    /**
     * @param mixed $quality
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setQuality($quality) {
        $this->_quality = $quality;
    }

    /**
     * AbsoluteQuality
     * @author Choate <choate.yao@gmail.com>
     * @return boolean
     */
    public function isAbsoluteQuality() {
        return $this->_absoluteQuality;
    }

    /**
     * @param boolean $absoluteQuality
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setAbsoluteQuality($absoluteQuality) {
        $this->_absoluteQuality = $absoluteQuality;
    }

    /**
     * Format
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getFormat() {
        return $this->_format;
    }

    /**
     * @param mixed $format
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setFormat($format) {
        $this->_format = $format;
    }


    /**
     * Storage
     * @author Choate <choate.yao@gmail.com>
     * @return BaseStorage
     */
    public function getStorage() {
        return $this->_storage;
    }

    /**
     * @param BaseStorage $storage
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setStorage($storage) {
        $this->_storage = \Yii::createObject($storage);
    }

    public function getTempPath() {
        $result = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->getStorage()->getBaseDir() . DIRECTORY_SEPARATOR;
        !is_dir($result) && @mkdir($result, 0777, true);

        return $result;
    }

    public function setTempPath($tempPath) {

    }

    /**
     * Driver
     * @author Choate <choate.yao@gmail.com>
     * @return string
     */
    public function getDriver() {
        return $this->_driver;
    }

    /**
     * @param string $driver
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setDriver($driver) {
        $this->_driver = $driver;
    }

    abstract public function save();
    
    /**
     * 保存新尺寸的图片
     * @author Hollis_Ho
     */
    abstract public function saveImg();
}