<?php

namespace app\models;

use app\models\base\BaseGuMonitor;

class GuMonitor extends BaseGuMonitor {
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 2;
    
    public static $status = [1 => '正常', 2 => '删除'];
    
    public static function getCodeName() {
        $codes = self::find()->select('code')->where(['status' => self::STATUS_NORMAL])->asArray()->column();
        $data = GuFix::find()->select('code,name')->where(['code' => $codes])->asArray()->all();

        $re = [];
        foreach ($data as $value) {
            $re[$value['code']] = $value['name'];
        }
        return $re;
    }
}