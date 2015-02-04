<?php
namespace diiimonn\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;

/**
 * Class RelationFollowBehavior
 * @package diiimonn\behaviors
 */
class RelationFollowBehavior extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    public $relations = [];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
        ];
    }

    public function afterSave()
    {
        $data = [];

        if (isset($_POST[$this->owner->formName()])) {
            $data = $_POST[$this->owner->formName()];
        }

        if ($data) {
            foreach ($data as $attribute => $value) {

                if (!in_array($attribute, $this->relations)) {
                    continue;
                }

                if (is_array($value)) {
                    $relation = $this->owner->getRelation($attribute);

                    if ($relation->via !== null) {
                        /** @var ActiveRecord $foreignModel */

                        $foreignModel = $relation->modelClass;
                        $this->owner->unlinkAll($attribute, true);

                        if (is_array(current($value))) {
                            foreach($value as $data) {
                                if (isset($data[$foreignModel::primaryKey()[0]]) && $data[$foreignModel::primaryKey()[0]] > 0) {
                                    $fm = $foreignModel::findOne($data[$foreignModel::primaryKey()[0]]);
                                    $fm->load($data, '');
                                    $this->owner->link($attribute, $fm);
                                }
                            }
                        } else {
                            foreach($value as $fk) {
                                if (preg_match('~^\d+$~', $fk)) {
                                    $this->owner->link($attribute, $foreignModel::findOne($fk));
                                }
                            }
                        }
                    }
                } else {
                    $this->owner->unlinkAll($attribute, true);
                }
            }
        }
    }
} 