ETextImage - Данное расширение для Yii Framework позволяет любой текст показывать "картинкой"
=======

## Установка

* Скачать ([zip](https://github.com/kosenka/ETextImage/zipball/master), [tar.gz](https://github.com/kosenka/ETextImage/tarball/master)).

* Распаковать архив в папку `application.extensions.ETextImage` . Должно получиться следующее:

```
protected/
├── components/
├── controllers/
├── ... application directories
└── extensions/
    ├── ETextImage/
    │   ├── fonts/
    │   └── ... другие файлы расширения ETextImage
    └── ... другие расширения
```

## ССылки

* [Demo](http://kosenka.ru/#tab1)
* [Extension project page](https://github.com/kosenka/ETextImage)
* [Russian community discussion thread](http://yiiframework.ru/forum/viewtopic.php?f=9&t=749)

## Использование

* Override CController::actions() and register an action of class ETextImageAction with ID 'textImage':
```php
	public function actions()
	{
		return array(
			'textImage'=>array(
				'class'=>'application.extensions.ETextImage.ETextImageAction',
			),
		);
	}
```

* In the controller view, insert a widget in the form.
```php
       <? $this->widget('application.extensions.ETextImage.ETextImage',
                        array(
                              'text' => "(495)1234567",
                              'fontSize' => 10,
                              'fontFile' => 'tahoma.ttf',
                              'transparent'=>false,
                              'foreColor'=>0x2040A0,
                              'backColor'=>0x55FF00,
                             )
                       );
```