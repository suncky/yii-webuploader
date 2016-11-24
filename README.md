#说明
扩展yii widget 的 上传组件

#使用样例
```php

config
'components' => [
//本地文件上传  上传到本地 设为uploader 关闭设为 uploader_local
'uploader' => [
    'class' => '\suncky\yii\widgets\webuploader\components\FileManager',
    'storage' => [
        'class' => '\suncky\yii\widgets\webuploader\components\Uploader',
        'basePath' => ROOT_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR,
        'baseUrl'   => Yii::getAlias('@static'),
        'baseDir'   => 'uploaded',
        //'class' => '\suncky\uploader\components\Oss',
        //'baseUrl' => 'http://.../',
        //'keyId'     => '...',
        //'keySecret' => '...',
        //'bucket'    => 'xxx-upload',
        //'endPoint'  => isset($_SERVER['SERVER']) && $_SERVER['SERVER'] == 'ALIYUN' ? 'http://oss-cn-hangzhou-internal.aliyuncs.com' : 'http://oss-cn-hangzhou.aliyuncs.com',
    ],
    'rules' => [
        'image' => [
            'class' => 'yii\validators\ImageValidator',
        ],
    ]
],
'imageManager' => [
    //'class' => '\suncky\yii\widgets\webuploader\components\OssImage',
    'class' => '\suncky\uploader\components\Image',
    'storage' => [
        'class' => '\suncky\yii\widgets\webuploader\components\Uploader',
        'basePath' => ROOT_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR,
        'baseUrl' => Yii::getAlias('@static'),
        'baseDir' => 'uploaded/thumb',

        // 'class' => 'suncky\yii\widgets\webuploader\components\Oss',
        // 'baseUrl' => 'http://.../',
        // 'baseDir' => 'uploaded',
        // 'keyId' => '...',
        // 'keySecret' => '...',
        // 'bucket' => 'xxx-upload',
        // 'endPoint' => isset($_SERVER['SERVER']) && $_SERVER['SERVER'] == 'ALIYUN' ? 'http://oss-cn-hangzhou-internal.aliyuncs.com' : 'http://oss-cn-hangzhou.aliyuncs.com',
    ],
],
]

controller.php
public function actions() {
        return [
            'image' => ImageUploaderAction::className(),
            'attachment'    => [
                'class' => AttachmentUploaderAction::className(),
                //'uploadDir' => 'uploaded' . DIRECTORY_SEPARATOR. 'app',
            ],
        ];
    }

model.php

上传图片
echo $form
->field($model, 'image_id')
->widget(ImageUploader::className(), [
   'template' => "<div class='uploader'>{pick}\n{upload}\n{items}</div>",
   'uploadOptions' => ['class' => 'uploader-upload'],
   'clientOptions' => [
       'fileVal' => 'upimg',
       'server' => Url::to(['image']),
       'fileNumLimit' => 1
   ]])
->label("上传图片");

上传附件
echo $form->field($model, 'attachment')
->widget(AttachmentUploader::className(), [
'template' => "<div class='uploader'>{pick}\n{upload}\n{items}</div>",
'uploadOptions' => ['class' => 'uploader-upload'],
 'clientOptions' =>
 ['fileVal' => 'upatt',
  'server' => Url::to(['attachment']),
  'fileNumLimit' => 1
  ]]);
```
