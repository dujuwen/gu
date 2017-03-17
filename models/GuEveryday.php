<?php

namespace app\models;

use app\models\base\BaseGuEveryday;

class GuEveryDay extends BaseGuEveryday {
    public static $changeData = []; //最近几天变化

    public static function getRecommend($day = 5, $num = 250) {
        $day = intval($day);
        $data = GuEveryDay::find()->select('code, incr_decr')->orderBy('date_ desc')->asArray()->all();
        $countNum = [];
        if (count($data)) {
            $re = [];
            foreach ($data as $value) {
                $code = $value['code'];
                if (isset($re[$code])) {
                    $countNum[$code] = $countNum[$code] + 1;
                    if ($countNum[$code] > $day) {
                        continue;
                    }
                    $re[$code] = $re[$code] + floatval($value['incr_decr']);
                } else {
                    $countNum[$code] = 1;
                    $re[$code] = floatval($value['incr_decr']);
                }
            }
            arsort($re);
            $re = array_slice($re, 0, $num);
            $names = GuFix::find()->select('code,name')->where(['code' => array_keys($re)])->asArray()->all();
            $namesNew = [];
            foreach ($names as $ns) {
                $namesNew[$ns['code']] = $ns['name'];
            }

            return [$re, $namesNew];
        }
    }

    public static function getChangeByCode($code, $day1 = 1, $day2 = 3, $day3 = 5) {
//         if (isset(self::$changeData[$code])) {
//             return self::$changeData[$code];
//         }
        $data = GuEveryDay::find()->select('incr_decr')->where(['code' => $code])->orderBy('date_ desc')->limit($day3 + 1)->column();
        $str = '';

        $t1 = 0;
        $t3 = 0;
        $t5 = 0;
        if (is_array($data) && count($data)) {
            $i = 0;
            $tmpCount = 0;
            foreach ($data as $num) {
                $i++;
                $tmpCount += round($num, 2);
                if ($day1 > 0 && $i == $day1) {
                    $t1 = $tmpCount;
                    $str .= '  |' . $tmpCount . '=>' . GuFix::getPecentByCode($code, $tmpCount);
                } else if ($day2 > 0 && $i == $day2) {
                    $t3 = $tmpCount;
                    $str .= '  |' . $tmpCount . '=>' . GuFix::getPecentByCode($code, $tmpCount);
                } else if ($day3 > 0 && $i == $day3) {
                    $t5 = $tmpCount;
                    $str .= '  |' . $tmpCount . '=>' . GuFix::getPecentByCode($code, $tmpCount);
                    break;
                }
            }
        } else {
            return ' NODATA';
        }

        if ($t1 > $t3) {
            $str .= '||1连+';
            if ($t1 >= 10000000) {
                $str .= '1千万';
            } else if ($t1 >= 20000000) {
                $str .= '2千万';
            } else if ($t1 >= 50000000) {
                $str .= '5千万';
            } else if ($t1 >= 100000000) {
                $str .= '1亿';
            }
        } else if ($t5 > $t3 && $t3 > $t1) {
            $str .= '||5连+';
            if ($t3 >= 10000000) {
                $str .= '1千万';
            } else if ($t3 >= 20000000) {
                $str .= '2千万';
            } else if ($t3 >= 50000000) {
                $str .= '5千万';
            } else if ($t3 >= 100000000) {
                $str .= '1亿';
            }
        }

        return $str;
    }
}