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

    /**
     CREATE TABLE `gu_change_1` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `code` varchar(255) NOT NULL DEFAULT '' COMMENT '股票代码',
      `yesterday` float NOT NULL DEFAULT '0' COMMENT '左收',
      `today` float NOT NULL DEFAULT '0' COMMENT '今开',
      `max` float NOT NULL DEFAULT '0' COMMENT '最高',
      `min` float NOT NULL DEFAULT '0' COMMENT '最低',
      `deal_count` int(11) NOT NULL DEFAULT '0' COMMENT '成交量',
      `deal_num` int(11) NOT NULL DEFAULT '0' COMMENT '成交额',
      `change_rate` float NOT NULL DEFAULT '0' COMMENT '换手率',
      `amplitude` float NOT NULL DEFAULT '0' COMMENT '振幅',
      `current_date` int(11) DEFAULT NULL COMMENT '当前时间(时间戳)',
      `current_date_` date DEFAULT NULL COMMENT '当前日期',
      `z_j_c` float NOT NULL DEFAULT '0' COMMENT '今日增减仓(单位万元)',
      `current` float NOT NULL DEFAULT '0' COMMENT '当前价格',
      `rate` float NOT NULL DEFAULT '0' COMMENT '当前价格相对增减比例',
      `sh_rate` float NOT NULL DEFAULT '0' COMMENT '上证涨跌幅度',
      `sh_num` float NOT NULL DEFAULT '0' COMMENT '上证涨跌额度',
      `sz_rate` float NOT NULL DEFAULT '0' COMMENT '深证涨跌幅度',
      `sz_num` float NOT NULL DEFAULT '0' COMMENT '深圳涨跌额度',
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
     */
}
