<?php
namespace suncky\yii\widgets\webuploader\components;

/**
 * Class OssImage
 * @package suncky\yii\widgets\webuploader\components
 */
class OssImage extends BaseImage
{
    public function init() {

    }

    public function save() {
        $params = [];
        if ($this->getWidth()) {
            $params['w'] = $this->getWidth();
        }
        if ($this->getHeight()) {
            $params['h'] = $this->getHeight();
        }
        $params['l'] = $this->getLarge();
        $params['e'] = $this->getPrefer();
        $params['p'] = $this->getProportion();
        $params['r'] = $this->getRotate();
        $params['sh'] = $this->getSharpen();
        $params[$this->isAbsoluteQuality() ? 'Q' : 'q'] = $this->getQuality();
        $result = [];
        foreach (array_filter($params, function($value) {return (bool)$value || is_numeric($value);}) as $key => $value) {
            $result[] = $value.$key;
        }

        //return $this->getStorage()->getBaseUrl() . $this->getStorage()->buildFileUrl($this->getFile()->getBasename()).$this->getFile()->getName() . '@' .implode('_', $result) . ($this->getFormat() ? '.'.$this->getFormat() : '');
        return $this->getStorage()->getBaseUrl() 
                . $this->getStorage()->buildFileUrl($this->getFile()->getBasename())
                . $this->getFile()->getName() 
                . '@' .implode('_', $result) 
                . ($this->getFormat() ? '.'.$this->getFormat() : substr(strrchr($this->getFile()->getName(), '.'), 0));
    }
    
    /**
     * @see \suncky\uploader\components\BaseImage::saveImg()
     */
    public function saveImg() {
        $params = [];
        if ($this->getWidth()) {
            $params['w'] = $this->getWidth();
        }
        if ($this->getHeight()) {
            $params['h'] = $this->getHeight();
        }
        $params['l'] = $this->getLarge();
        $params['e'] = $this->getPrefer();
        $params['p'] = $this->getProportion();
        $params['r'] = $this->getRotate();
        $params['sh'] = $this->getSharpen();
        $params['c'] = $this->getCrop();
        $params[$this->isAbsoluteQuality() ? 'Q' : 'q'] = $this->getQuality();
        $result = [];
        foreach (array_filter($params, function($value) {return (bool)$value || is_numeric($value);}) as $key => $value) {
            $result[] = $value.$key;
        }
        
        $name = basename($this->getImgUrl());
        $basename = basename($this->getImgUrl(), substr(strrchr($name, '.'), 0));
        return $this->getStorage()->getBaseUrl()
                . $this->getStorage()->buildFileUrl($basename)
                . $name
                . '@' .implode('_', $result)
                . ($this->getFormat() ? '.'.$this->getFormat() : substr(strrchr($name, '.'), 0));
    }
}