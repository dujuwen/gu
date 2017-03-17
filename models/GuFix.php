<?php

namespace app\models;

use app\models\base\BaseGuFix;

class GuFix extends BaseGuFix {
    const TYPE_SH = 'sh'; //上证
    const TYPE_SZ = 'sz'; //深证

    public static $types = [
        self::TYPE_SH => 1,
        self::TYPE_SZ => 2
    ];

    public static $types2 = [
        1 => '上证',
        2 => '深证',
    ];

    //返回形式:600149(廊坊发展)
    public static function getNameByCode($code) {
        $model = self::findOne(['code' => $code]);
        return $model ? ($code . '(' . $model->name . ')') : $code;
    }

    //获得买入流通率
    public static function getPecentByCode($code, $num) {
        if (!$code || $num <= 0) {
            return 0;
        }

        $percent = 0;
        $leftNum = GuFix::find()->where(['code' => $code])->select('left_num')->column();
        if (count($leftNum) && $leftNum[0] > 100) {
            //$num的单位是万
            $tmpPercent = $percent = $num * 1000000 / $leftNum[0];
            $percent = round($percent, 2) . '%';
            if ($tmpPercent > 10) {
                $percent = "\033[0;31m$percent!!!\x1B[0m";
            } elseif ($tmpPercent > 5) {
                $percent = "\033[0;31m$percent\x1B[0m";
            } elseif ($tmpPercent > 2) {
                $percent = "\033[0;32m$percent\x1B[0m";
            }
        }

        return $percent;
    }
}