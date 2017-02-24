<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_everyday".
 *
 * @property string $id
 * @property string $code
 * @property double $begin
 * @property double $end
 * @property double $max
 * @property double $min
 * @property double $incr_decr
 * @property string $date_
 */
class BaseGuEveryday extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gu_everyday';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin', 'end', 'max', 'min', 'incr_decr'], 'number'],
            [['date_'], 'safe'],
            [['code'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '代码',
            'begin' => '开盘',
            'end' => '收盘',
            'max' => '最高',
            'min' => '最低',
            'date_' => '日期',
            'incr_decr' => '增减仓',
        ];
    }
}
