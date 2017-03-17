<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_fix".
 *
 * @property integer $id
 * @property integer $type
 * @property string $code
 * @property string $name
 * @property string $pingyin
 * @property integer $total
 * @property integer $circulation
 * @property double $hand_rate
 * @property integer $hand_num
 * @property integer $left_num
 * @property string $zjc_five_day
 * @property double $day1
 * @property double $day3
 * @property double $day5
 * @property double $rate1
 * @property double $rate3
 * @property double $rate5
 */
class BaseGuFix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gu_fix';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'total', 'circulation', 'hand_num', 'left_num'], 'integer'],
            [['hand_rate', 'day1', 'day3', 'day5', 'rate1', 'rate3', 'rate5'], 'number'],
            [['zjc_five_day'], 'string'],
            [['name', 'pingyin', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '所属证券交易所',
            'code' => '股票代码',
            'name' => '股票名称',
            'pingyin' => '股票名称拼音',
            'total' => '总市值',
            'circulation' => '流通市值',
            'hand_rate' => '前10股东持有总市值比例',
            'hand_num' => '前10股东持有总市值',
            'left_num' => '实际可以流通的总市值',
            'zjc_five_day' => '5日增加仓',
        ];
    }
}
