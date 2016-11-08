<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader\components;

/**
 * Class OssFile
 * @package suncky\uploader\components
 * @author Choate <choate.yao@gmail.com>
 */
class OssFile extends File
{
    public function init() {
        $info = $this->getPathinfo();
        $this->_path = $info['dirname'];
        $this->_basename = $info['basename'];
        $this->_extension = $info['extension'];
        $this->_name = $info['filename'];
        $this->_size = 0;
        $this->_mime = null;
    }

    protected function getPathinfo() {
        $filename = substr(strrchr($this->getPathname(), DIRECTORY_SEPARATOR), 1);
        $basename = substr($filename, 0, strrpos($filename, '.') ? strrpos($filename, '.') : strlen($filename));
        $extension = substr(strrchr($filename, '.'), 1) ?: '';
        $dirname = substr($this->getPathname(), 0, strrpos($this->getPathname(), DIRECTORY_SEPARATOR));

        return ['dirname' => $dirname, 'filename' => $filename, 'basename' => $basename, 'extension' => $extension];
    }
}