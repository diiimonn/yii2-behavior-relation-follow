# yii2-behavior-relation-follow
Model behavior for relation data management

## Installation

To install with composer:

```
$ php composer.phar require diiimonn/yii2-behavior-relation-follow "dev-master"
```

or add

```
"diiimonn/yii2-behavior-relation-follow": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

### in MyModel.php

```php
...
use diiimonn\behaviors\RelationFollowBehavior;
...

public function behaviors()
{
    return [
        ...
        [
            'class' => RelationFollowBehavior::className(),
            'relations' => [
                'books', // relation name
            ]
        ],
    ];
}

public function getBooks()
{
    return $this->hasMany(BookModel::className(), ['id' => 'book_id']);
}
...
```

### in my-view/_form.php
Select multiple from relation model. Recommended use [yii2-widget-checkbox-multiple](https://github.com/diiimonn/yii2-widget-checkbox-multiple)