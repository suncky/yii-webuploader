<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 suncky.com all rights reserved.
 */
namespace suncky\yii\widgets\webuploader\components;


use Aliyun\OSS\Models\CompleteMultipartUploadResult;
use Aliyun\OSS\Models\PutObjectResult;
use suncky\oss\AliyunOSS;
use Yii;
use Aliyun\OSS\OSSClient;
use yii\log\Logger;

class Oss extends BaseStorage
{
    protected $_keyId;
    protected $_keySecret;
    protected $_bucket;
    protected $_object;
    protected $_fileKey;
    protected $_fileObject;
    protected $_client;
    protected $_maxSize = 10000000;
    public $endPoint = 'http://oss-cn-hangzhou.aliyuncs.com';

    public function __construct($config = []) {
        parent::__construct($config);
        new AliyunOSS();
    }


    public function save(File $uploadedFile, $isDelete = false) {
        $fileId   = $this->hashFile($uploadedFile);
        $ext = $this->getFileExt(substr($fileId, -3));
        $filePath = $this->buildFileUrl($fileId, true) . $fileId . ($ext ? ".{$ext}" : '');
        $result = $this->upload($uploadedFile, $filePath);
        if ($result) {
            $isDelete && @unlink($uploadedFile->getPathname());

            return $fileId;
        }

        return false;
    }

    public function getFilePath($fileHash) {
        if (!$fileHash || strlen($fileHash) != 35) {
            return null;
        }
        $ext = $this->getFileExt(substr($fileHash, -3));

        return $this->buildFileUrl($fileHash) . $fileHash . ($ext ? ".{$ext}" : '');
    }

    public function getFileUrl($fileHash) {
        if (!$fileHash || strlen($fileHash) != 35) {
            return null;
        }
        $ext = $this->getFileExt(substr($fileHash, -3));

        return $this->getBaseUrl() . $this->buildFileUrl($fileHash) . $fileHash . ($ext ? ".{$ext}" : '');
    }

    /**
     * @param string $fileHash
     *
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     * @return OssFile
     */
    public function getFile($fileHash) {
        if ($this->getFilePath($fileHash)) {
            return new OssFile(['pathname' => $this->getFilePath($fileHash)]);
        }

        return null;
    }

    private function multiUpload(File $uploadedFile, $file) {
        Yii::$app->log->logger->log('multiUpload begin.', Logger::LEVEL_TRACE);
        $num = ceil($uploadedFile->getSize() / $this->_maxSize);
        $source = @fopen($uploadedFile->getPathname(), 'r');
        try {
            $uploadResult = $this->getClient()->initiateMultipartUpload(array_merge([
                    'Bucket'             => $this->_bucket,
                    'Key'                => ltrim($file, '/'),
                    'Content'            => $source,
                    'ContentLength'      => $uploadedFile->getSize(),
                    'ContentType'        => $uploadedFile->getMime()
                ], (strpos($uploadedFile->getMime(), 'image') === false ? ['ContentDisposition' => "attachment; filename={$uploadedFile->getName()}"] : []))
            );
            $partItems    = [];
            for ($i = 0; $i < $num; $i++) {
                $buf         = fread($source, $this->_maxSize);
                $partItems[] = $this->getClient()->uploadPart([
                        'Bucket'     => $this->_bucket,
                        'Key'        => $file,
                        'UploadId'   => $uploadResult->getUploadId(),
                        'Content'    => $buf,
                        'PartNumber' => ($i + 1),
                        'PartSize'   => strlen($buf)
                    ]
                )->getPartETag();
            }
            $result = $this->getClient()->completeMultipartUpload([
                    'Bucket'    => $this->_bucket,
                    'Key'       => ltrim($file, '/'),
                    'UploadId'  => $uploadResult->getUploadId(),
                    'PartETags' => $partItems
                ]
            );
            Yii::$app->log->logger->log('multiUpload end.' . $result->getLocation(), Logger::LEVEL_TRACE);

            return $result instanceof CompleteMultipartUploadResult;
        } finally {
            @fclose($source);
        }

        return false;
    }

    private function upload(File $uploadedFile, $file) {
        Yii::$app->log->logger->log('common Upload begin.', Logger::LEVEL_TRACE);
        $source = @fopen($uploadedFile->getPathname(), 'r');
        try {
            return $this->getClient()->putObject(array_merge([
                    'Bucket'        => $this->_bucket, 'Key' => ltrim($file, '/'),
                    'Content'       => $source,
                    'ContentLength' => $uploadedFile->getSize(), 'ContentType' => $uploadedFile->getMime()
                ], (strpos($uploadedFile->getMime(), 'image') === false ? ['ContentDisposition' => "attachment; filename={$uploadedFile->getName()}"] : []))
            ) instanceof PutObjectResult;
        } finally {
            @fclose($source);
        }

        return false;
    }


    public function setEndPoint($endpoint) {
        $this->endPoint = $endpoint;
    }

    public function getEndPoint() {
        return $this->endPoint;
    }

    /**
     * 设置文件的key
     *
     * @param string $keyId
     */
    public function setKeyId($keyId) {
        $this->_keyId = $keyId;
    }

    /**
     * 设置文件的secret
     *
     * @param string $keySecret
     */
    public function setKeySecret($keySecret) {
        $this->_keySecret = $keySecret;
    }

    /**
     * 设置文件的bucket
     *
     * @param string $bucket
     */
    public function setBucket($bucket) {
        $this->_bucket = $bucket;
    }

    public function setClient($client) {
        $this->_client = $client;
    }

    public function getClient() {
        if (!$this->_client) {
            $this->_client = OSSClient::factory(['Endpoint' => $this->endPoint, 'AccessKeyId' => $this->_keyId, 'AccessKeySecret' => $this->_keySecret]);
        }

        return $this->_client;
    }

    /**
     * 获取get的文件的OSS上的key
     * return string
     */
    public function getFileKey() {
        return $this->_fileKey;
    }

    /**
     * 获取get的文件的内容
     * return string
     */
    public function getFileObject() {
        return $this->_fileObject;
    }

}
