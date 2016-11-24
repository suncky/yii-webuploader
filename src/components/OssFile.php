<?php
namespace suncky\yii\widgets\webuploader\components;

/**
 * Class OssFile
 * @package suncky\yii\widgets\webuploader\components
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