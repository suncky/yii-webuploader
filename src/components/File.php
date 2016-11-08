<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader\components;

use yii\helpers\ArrayHelper;
use Yii;
use yii\base\Object;
use yii\helpers\FileHelper;

/**
 * Class File
 * @package sjy\upload\components
 * @author Choate <choate.yao@gmail.com>
 */
class File extends Object
{
    protected $_basename;
    protected $_name;
    protected $_mime;
    protected $_extension;
    protected $_size;
    protected $_path;
    protected $_pathname;

    public function init() {
        $info = pathinfo($this->getPathname());
        $name = $this->getName() ?: ArrayHelper::getValue($info, 'basename');
        $this->_path = ArrayHelper::getValue($info, 'dirname');
        $this->_basename = $this->getName() ? substr($name, 0, strrpos($name, '.') ? strrpos($name, '.') : strlen($name)) :ArrayHelper::getValue($info, 'filename');
        $this->_mime = FileHelper::getMimeType($this->getPathname());
        $this->_extension = substr(strrchr($name, '.'), 1) ?: '';
        $this->_name = $name;
        $this->_size = filesize($this->getPathname());
    }

    /**
     * Basename
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getBasename() {
        return $this->_basename;
    }

    /**
     * Name
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Mime
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getMime() {
        return $this->_mime;
    }

    /**
     * Size
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getSize() {
        return $this->_size;
    }

    /**
     * Path
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * Pathname
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getPathname() {
        return $this->_pathname;
    }

    /**
     * @param mixed $pathname
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setPathname($pathname) {
        $this->_pathname = $pathname;
    }

    /**
     * Extension
     * @author Choate <choate.yao@gmail.com>
     * @return mixed
     */
    public function getExtension() {
        return $this->_extension;
    }

    /**
     * @param mixed $name
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setName($name) {
        $this->_name = $name;
    }


}