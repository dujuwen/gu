<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gu_change_1".
 *
 * @property integer $id
 * @property string $code
 * @property double $yesterday
 * @property double $today
 * @property double $max
 * @property double $min
 * @property integer $deal_count
 * @property integer $deal_num
 * @property double $change_rate
 * @property double $amplitude
 * @property integer $current_date
 * @property string $current_date_
 * @property double $z_j_c
 * @property double $current
 * @property double $rate
 * @property double $sh_rate
 * @property double $sh_num
 * @property double $sz_rate
 * @property double $sz_num
 * @property string $created_at
 */
class BaseGuChange1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gu_change_1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yesterday', 'today', 'max', 'min', 'change_rate', 'amplitude', 'current', 'rate', 'sh_rate', 'sh_num', 'sz_rate', 'sz_num', 'z_j_c'], 'number'],
            [['deal_count', 'deal_num', 'current_date'], 'integer'],
            [['current_date_', 'created_at'], 'safe'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '股票代码',
            'yesterday' => '昨收',
            'today' => '今开',
            'max' => '最高',
            'min' => '最低',
            'deal_count' => '成交量(手)',
            'deal_num' => '成交额(万)',
            'change_rate' => '换手率',
            'amplitude' => '振幅',
            'current_date' => '当前时间',
            'current_date_' => '当前日期',
            'z_j_c' => '今日增减仓(万)',
            'current' => '当前价格',
            'rate' => '价格波动比例',
            'sh_rate' => '上证涨跌幅度',
            'sh_num' => '上证指数值',
            'sz_rate' => '深证涨跌幅度',
            'sz_num' => '深证指数值',
            'created_at' => '创建时间',
        ];
    }
}
