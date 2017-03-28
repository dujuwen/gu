<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\library\Util;
use yii\helpers\StringHelper;
use app\models\GuFix;
use app\models\GuChange1;
use app\models\GuEveryDay;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     //一周一次,固定数据导入
      ./yii hello/fix

     //每天下午3点后执行一次
     ./yii hello/fix-info

     //实时买入
     ./yii hello/g

     //实时价格(单价)
     ./yii hello/real

     //获得最近五日增减仓情况,每天执行一次
     ./yii hello/five-day
     */

    /**
     * This command echoes what you have entered as the message.
     *
     * /Users/junwendu/Project/github/gu
     * gu git:(master) ./yii hello/index
     *
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        //获取上证和深证指数地址
        //$sh_zs_index_url = 'http://qt.gtimg.cn/r=0.3978993779751039q=s_sh000001,s_sz399001';
        //数据格式
        //v_s_sh000001 = "1~上证指数~000001~3212.99~-4.94~-0.15~241507706~25796524~~";
        //v_s_sz399001 = "51~深证成指~399001~10177.25~-87.67~-0.85~184658833~26178875~~";
        if (date('H:i:s') > '15:00:10') {
            $this->actionFix();
            sleep(2);
            $this->actionFixInfo();
            sleep(2);
            $this->actionFiveDay();
            sleep(2);
            $this->actionC();
        }
    }

    //固定数据导入,获取基本code
    //这个一周执行一次
    //./yii hello/fix
    public function actionFix()
    {
        $fix_url = 'http://stock.gtimg.cn/data/index.php?appn=rank&t=ranka/last&o=1&l=80&v=list_data&p=';

        for ($page = 1; $page < 40; $page++) {
            try {
                $data = Util::curl($fix_url . $page);
                $pos = strpos($data, "data:'");
                $strData = rtrim(substr($data, $pos + 6), "'};");
                $arrData = explode(',', $strData);
                if (is_array($arrData) && count($arrData)) {
                    foreach ($arrData as $idStr) {
                        $code = substr($idStr, 2);

                        $model = GuFix::findOne(['code' => $code]);
                        if ($model) {
                            continue;
                        }

                        $model = new GuFix();
                        $model->code = $code . '';
                        if (StringHelper::startsWith($idStr, GuFix::TYPE_SH)) {
                            $model->type = GuFix::$types[GuFix::TYPE_SH];
                        } else {
                            $model->type = GuFix::$types[GuFix::TYPE_SZ];
                        }
                        $re = $model->save();
                        if ( ! $re) {
                        	var_dump($model->getErrors());die;
                        }
                    }
                } else {
                    break;
                }
                $page++;
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    //获取固定信息,每天下午三点后执行一次
    //./yii hello/fix-info
    public function actionFixInfo()
    {
        $all = GuFix::find()->select('type,code')->asArray()->all();
        $i = 1;
        foreach ($all as $value) {
            $type = $value['type'];
            $code = $value['code'];
            $model = GuFix::findOne(['code' => $code]);

            if ($model) {
                //名称更新的话一周执行一次
                $name = $this->getCodeName($type, $code);
                $model->name = $name;
                //下面是更新总的市值和流通市值
                $data = $this->getChangeData($type, $code);
                if (is_array($data) && count($data) && isset($data[45]) && isset($data[44])) {
                    //44是流通的
                    //45是总的
                    $total = round($data[45], 3) * 100000000;//单位是亿要计算下
                    if ($total % 100 != 0) {
                        $total = 100 + $total - ($total % 100);
                    }
                    $model->total = $total;

                    $circulation = $data[44] * 100000000; //单位是亿要计算下
                    if ($circulation % 100 != 0) {
                        $circulation = 100 + $circulation - ($circulation % 100);
                    }
                    $model->circulation = $circulation;

                    $bigRate = floatval($this->getBigPercent($code)) / 100;
                    $model->hand_rate = $bigRate;

                    $hand_num = ceil($model->circulation  * $bigRate);
                    if ($hand_num % 100 != 0) {
                        $hand_num = 100 + $hand_num - ($hand_num % 100);
                    }
                    $model->hand_num = $hand_num;

                    $model->left_num = $model->circulation - $model->hand_num;
                    $model->left_num = intval($model->left_num);
                    $re = $model->save();
                    if ($re) {
                        echo $code . 'update success, ' . $i . PHP_EOL;
                        $i++;
                    } else {
                    	var_dump($model->getErrors());die;
                    }
                }
            }
        }
    }

    //获取总共的股本数,包括浮动数
    //1=>名称, 2=>代码, 3=>当前价格, 4=>左收, 5=>今开, 6=>成交手数, 7外盘=>, 8=>内盘,
    //31=>当前波动价格, 32=>当前波动比例, 33=>当天最高, 34=>当天最低, 37=>成交额, 38=>换手率,
    //43=>振幅, 44=>流通市值, 45=>总市值, 46=>市净率
    private function getChangeData($type, $code) {
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = ($type == 1) ? 'sh' : 'sz';
            $gu_ben_info = 'http://qt.gtimg.cn/'. $rand .'q=' . $tmp . $code;
            $data = Util::curl($gu_ben_info);
            $arrData = explode('~', $data);
            return $arrData;
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //获取大股东持股比例
    private function getBigPercent($code) {
        //股东股份信息
        try {
            $gu_don_info = 'http://stock.finance.qq.com/corp1/stk_holder.php?zqdm=' . $code;
            $data = Util::curl($gu_don_info);
            $percent_pos = strpos($data, 'class="nobor_l fntB"');
            $big_percent_str = substr($data, $percent_pos, 500); //所有大股东持有比例
            preg_match('/([\d\.]*)%/', $big_percent_str , $matches);
            if (is_array($matches) && count($matches) >= 2) {
                $big_percent = $matches[1];
                return $big_percent;
            }
        } catch (\Exception $e) {
            return 0;
        }

        return 0;
    }

    //获取股票名称
    private function getCodeName($type, $code) {
        try {
            $code = (($type == 1) ? 'sh' : 'sz') . $code;
            $code_name_url = 'http://qt.gtimg.cn/q=s_' . $code;
            $data = Util::curl($code_name_url, 0, '', ["charset=UTF-8"]);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $arrNames = explode('~', $data);
                return $arrNames[1];
            }
        } catch (\Exception $e) {
            return '';
        }

        return '';
    }

    //获取动态信息
    //./yii hello/c
    //后面优化下请求,多次请求合并!!
    public function actionC()
    {
//         $change_url = 'http://web.sqt.gtimg.cn/q=sh600650?r=0.2256620838672143';
//         'http://qt.gtimg.cn/q=s_sh600650'; //最终成交
//         'http://qt.gtimg.cn/r=0.6789q=sh600650'; //实时数据
//         $change_url = 'http://qt.gtimg.cn/r=0.5693976059187198q=s_sz000659';

            //$codes = GuMonitor::find()->select('code')->where(['status' => GuMonitor::STATUS_NORMAL])->asArray()->column();
            $codes = GuFix::find()->select('code')->asArray()->column();
            $i = 0;
            foreach ($codes as $code) {
                $i++;
                $change = GuChange1::findOne(['current_date_' => date('Y-m-d'), 'code' => $code]);
                if ($change) {
                	$tmp = $this->getAllData($code);
                    $change->z_j_c = count($tmp) && isset($tmp[3]) ? $tmp[3] : 0;
                    if ($change->save()) {
                        echo $code . ' update success! ' . $i . PHP_EOL;
                    }
                    continue;
                }
                $model = new GuChange1();
                $model->code = $code;
                $tmp = $this->getAllData($code);
                $model->z_j_c = count($tmp) && isset($tmp[3]) ? $tmp[3] : 0;

                $model->current_date = time();
                $model->current_date_ = date('Y-m-d');

                //key 1名称, 2代码, 3当前价格, 4涨跌额, 5涨跌百分百, 6成交手数(手), 7成交额(万)
                $realData = $this->getTodayChange($code); //成交数据
                if (is_array($realData) && count($realData)) {
                    $model->deal_count = floatval($realData[6]); //成交量(手)
                    $model->deal_num = floatval($realData[7]); //成交额(万)
                }

                //key 3当前价格, 4左收, 5今开, 31价格波动值, 32价格波动比例, 33最高, 34最低, 38换手率, 43振幅
                $realData2 = $this->getTodayChange2($code); //价格数据
                if (is_array($realData2) && count($realData2)) {
                    $model->current = floatval($realData2[3]); //当前价格
                    $model->yesterday = floatval($realData2[4]); //左收
                    $model->today = floatval($realData2[5]); //今开
                    $model->rate = floatval($realData2[32]); //当前浮动百分比
                    $model->max = floatval($realData2[33]); //最高
                    $model->min = floatval($realData2[34]); //最低
                    $model->change_rate = floatval($realData2[38]); //换手率
                    $model->amplitude = floatval($realData2[43]); //振幅
                }

                $shsz = $this->getShSz();
                if (is_array($shsz)) {
                    $model->sh_num = Util::get($shsz, 1, 0); //上证指数
                    $model->sh_rate = Util::get($shsz, 2, 0); //上证比例
                    $model->sz_num = Util::get($shsz, 3, 0); //深证指数
                    $model->sz_rate = Util::get($shsz, 4, 0); //深证比例
                }

                $re = $model->save();
                if ( ! $re) {
                	var_dump($model->getErrors());die;
                }

                echo $code . ' add success! ' . $i . PHP_EOL;
            }
    }

    //获得主力增减仓
    //3=>增加仓
    private function getAllData($code) {
        try {
            if (!$code) {
            	return [];
            }

            //http://qt.gtimg.cn/r=0.04984568467072226&q=ff_sz002351,ff_sh600682
            $rand = mt_rand() / mt_getrandmax();
            if (is_array($code) && count($code)) {
                $code = array_values($code);
                $codeStr = '';
            	foreach ($code as $c) {
            	    $codeStr .= 'ff_' . $c . ',';
            	}
            	$codeStr = rtrim($codeStr, ',');
            	$zjc_url = 'http://qt.gtimg.cn/r='. $rand .'&q=' . $codeStr;
            	$data = Util::curl($zjc_url);
            	if ($data) {
                	$re = [];
            	    $data = explode(';', $data);
            	    if (is_array($data) && count($data)) {
            	    	foreach ($data as $value) {
            	    		$tmp = explode('~', $value);
            	    		if (is_array($tmp) && count($tmp) == 18) {
            	    		    //3是当然增减仓,13日期,14~17过去几天
            	    		    //"20170220^10003.25^10796.03"
            	    		    //日期=>当天增减额
                	    		$tmpCode = substr($tmp[0], -6) . '';
            	    			$re[$tmpCode][$tmp[13]] = $tmp[3];
            	    			for ($j = 14; $j <= 17; $j++) {
                	    			$t14 = explode('^', $tmp[$j]);
                	    			if (count($t14) == 3) {
                    	    			$t14 = array_values($t14);
                    	    			$diff = floatval($t14[1]) - floatval($t14[2]);
                	    				$re[$tmpCode][$t14[0]] = $diff;
                	    			}
            	    			}
            	    		}
            	    	}
            	    }
            	    return $re;
            	}
            } else {
                $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
                $code = $tmp . $code;
                $zjc_url = 'http://qt.gtimg.cn/r='. $rand .'&q=ff_' . $code;
                $data = Util::curl($zjc_url);
                if ($data) {
                    $data = explode('~', $data);
                    return $data;
                }
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage(), 'djw');die;
            return [];
        }

        return [];
    }

    //获得数据类似v_s_sh600425="1~青松建化~600425~7.04~0.37~5.55~1538364~106235~~97.07";
    //数组key 1名称, 2代码, 3当前价格, 4涨跌额, 5涨跌百分百, 6成交手数(手), 7成交额(万)
    private function getTodayChange($code) {
        //http://qt.gtimg.cn/r=0.988934817751348q=s_sh600425 //简单数据url
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
            $code = $tmp . $code;
            $url = 'http://qt.gtimg.cn/r='. $rand .'&q=s_' . $code;
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $data = explode('~', $data);
                return $data;
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //获得类似数据v_sh600149="1~廊坊发展~600149~22.88~23.17~23.18~109920~48567~61353~22.88~51~22.87~250~22.86~108~22.85~854~22.84~73~22.90~510~22.92~101~22.93~7~22.94~1~22.95~110~
    //11:30:02/22.88/12/S/27456/15178|11:29:58/22.88/24/S/54912/15172|11:29:56/22.90/1/B/2290/15163|11:29:49/22.90/11/B/25190/15151|11:29:47/22.88/500/S/1144261/15147|11:29:37/22.92/2/B/4584/15129~20170217113553~
    //-0.29~-1.25~23.38~22.81~22.88/109908/252957682~109920~25299~2.89~~~23.38~22.81~2.46~86.98~86.98~44.98~25.49~20.85~0.83";
    //key 3当前价格, 4左收, 5今开, 31价格波动值, 32价格波动比例, 33最高, 34最低
    private function getTodayChange2($code) {
        //http://web.sqt.gtimg.cn/q=sh600149?r=0.0756269266558307 //详细数据url
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
            $code = $tmp . $code;
            $url = 'http://web.sqt.gtimg.cn/q='. $rand .'&q=' . $code;
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $data = explode('~', $data);
                return $data;
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //获得当前上证和深证指数
    /**
     array(4) {
      [1] =>
      string(7) "3214.62" //上证
      [2] =>
      string(5) "-0.46" //比例
      [3] =>
      string(8) "10214.65" //深证
      [4] =>
      string(5) "-0.38" //比例
    }
     */
    private function getShSz() {
        //http://qt.gtimg.cn/r=0.6956423004204846q=s_sh000001,s_sz399001
        static $re = [];
        if (count($re) > 0) {
        	return $re;
        }

        try {
            $rand = mt_rand() / mt_getrandmax();
            $url = 'http://qt.gtimg.cn/r='. $rand .'q=s_sh000001';
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $data = explode('~', $data);
                if (is_array($data) && count($data)) {
                    $re[1] = $data[3]; //值
                    $re[2] = $data[5]; //比例
                }
            }

            $rand = mt_rand() / mt_getrandmax();
            $url = 'http://qt.gtimg.cn/r='. $rand .'q=s_sz399001';
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            if ($data) {
                $data = explode('~', $data);
                if (is_array($data) && count($data)) {
                    $re[3] = $data[3]; //值
                    $re[4] = $data[5]; //比例
                }
            }
        } catch (\Exception $e) {
            return $re;
        }

        return $re;
    }

    //显示买入大于一定额度的
    // ./yii hello/g
    public function actionG() {
        $codes = GuFix::find()->select('code')->asArray()->column();
        $signleHadle = 100;
        $codesNew = [];
        foreach ($codes as $code) {
            if (!StringHelper::startsWith($code, '3')) {
                $codesNew[] = (StringHelper::startsWith($code, '6') ? 'sh' : 'sz') . $code;
            }
        }
        $chunkArr = array_chunk($codesNew, $signleHadle);
        $final = [];
        foreach ($chunkArr as $limit) {
            $this->getGao($limit);
        }
    }

    private function getGao($limit) {
        //http://web.sqt.gtimg.cn/q=sh601992,sh600720?r=0.0756269266558307
        try {
            if (!is_array($limit)) {
                return [];
            }

            $startNum = 1200000;
            $rand = mt_rand() / mt_getrandmax();
            $str = implode(',', $limit);
            $url = 'http://web.sqt.gtimg.cn/q='. $str .'?r=' . $rand;
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            $re = [];
            if ($data) {
                $data = explode(';', $data);
                foreach ($data as $value) {
                    $tmp1 = explode('~', $value);
                    //暂时29是实时数据
                    $td = $tmp1[29]; //名称
                    $code = $tmp1[2]; //code
                    $td2 = $code . '/' . $tmp1[1];
                    $tmp = explode('/', $td);
                    if (is_array($tmp)) {
                        $tmp = array_chunk($tmp, 5, false);
                        foreach ($tmp as $tvalue) {
                            $tvalue = array_values($tvalue);
                            if (count($tvalue) == 5 && $tvalue[3] == 'B') {
                                $total = $tvalue[1] * $tvalue[2] * 100;
                                if ($total > $startNum) {
                                    $curPrice = $this->getTodayChange($code);
                                    //600221/****/1710000/0.29
                                    $total = ($total > 5000000) ? ($total >= 10000000 ? $total . '(千万)' : $total . '(百万)') : $total;
                                    $all = $this->getAllData($code);
                                    if (isset($all[3])) {
                                        $total .= "[{$all[3]}]";
                                    }
                                    echo $td2 . '/'  . $total . '/' . $curPrice[5] . GuEveryDay::getChangeByCode($code) . PHP_EOL;
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //实时价格
    public function actionReal($code) {
        if (!$code) {
            die('code为空');
        }

        $data = $this->getTodayChange($code);
        if (is_array($data) && count($data) > 5) {
            $tmp = $this->getAllData($code);
            $tmp = isset($tmp[3]) ? $tmp[3] : 0;
            echo "{$data[1]}/{$data[2]}/{$data[3]}/{$data[5]}/[$tmp]" . GuEveryDay::getChangeByCode($code) . PHP_EOL;
        } else {
            echo '出错了!' . PHP_EOL;
        }
    }

    //获得最近五日增减仓情况,每天执行一次
    //yii hello/f
    public function actionFiveDay() {
        //60分
        //http://ifzq.gtimg.cn/appstock/app/kline/mkline?param=sz002351,m60,,320&_var=m60_today&r=0.5741375416101258
        $codes = GuFix::find()->select('code')->asArray()->column();
        $signleHadle = 50;
        $codesNew = [];
        foreach ($codes as $code) {
            $codesNew[] = (StringHelper::startsWith($code, '6') ? 'sh' : 'sz') . $code;
        }
        $chunkArr = array_chunk($codesNew, $signleHadle);
        foreach ($chunkArr as $limit) {
            $zjcArr = $this->getAllData($limit);
            $i = 0;
            foreach ($zjcArr as $cod => $fiveZjcData) {
                $re = $this->getRecentMaxMin($cod);
                if (count($fiveZjcData)) {
                    foreach ($fiveZjcData as $day => $zjcNum) {
                        $date = date('Y-m-d', strtotime($day));
                        $exist = GuEveryDay::findOne(['code' => $cod, 'date_' => $date]);
                        if ($exist) {
                        	continue;
                        }

                    	$model = new GuEveryDay();
                    	$model->code = $cod . '';
                    	$model->date_ = $date;
                    	$model->incr_decr = floatval($zjcNum);
                    	if (is_array($re) && count($re) && isset($re[$day])) {
                        	$model->begin = $re[$day]['begin'];
                        	$model->end = $re[$day]['end'];
                        	$model->max = $re[$day]['max'];
                        	$model->min = $re[$day]['min'];
                    	}
                    	if ($model->save()) {
                    	    $i++;
                    	} else {
                    		var_dump($model->getErrors());die;
                    	}
                    }
                }
            }
            echo $i . ' add success!' . PHP_EOL;
        }

        GuEveryDay::updateChangeData();
    }

    //获取最近一段时间的波动价格
    private function getRecentMaxMin($code) {
    	//http://ifzq.gtimg.cn/appstock/app/kline/mkline?param=sz002351,m60,,320&_var=m60_today&r=0.5741375416101258
        try {
            $rand = mt_rand() / mt_getrandmax();
            $tmp = StringHelper::startsWith($code, '6') ? 'sh' : 'sz';
            $code = $tmp . $code;
            $url = 'http://ifzq.gtimg.cn/appstock/app/kline/mkline?param='. $code .',m60,,320&_var=m60_today&r=' . $rand;
            $data = Util::curl($url);
            $data = iconv('GBK', 'utf-8', $data);
            $fre = [];
            if ($data) {
                $data = ltrim($data, 'm60_today=');
                $re = json_decode($data, true);
                if (is_array($re) && count($re) && isset($re['data'][$code])) {
                    $data = $re['data'][$code];
                    if (is_array($data)) {
                        $forData = array_pop($data);
                    	if (is_array($forData)) {
                    		foreach ($forData as $value) {
                    			if (count($value) == 6 && is_array($value)) {
                    				$ymd = date('Ymd', strtotime($value[0]));
                    				if (isset($fre[$ymd])) {
                        				$fre[$ymd] = ['begin' => Util::smaller($value[1], $fre[$ymd]['begin']), 'end' => Util::bigger($value[2], $fre[$ymd]['end']), 'max' => Util::bigger($value[3], $fre[$ymd]['max']), 'min' => Util::smaller($value[4], $fre[$ymd]['min'])];
                    				} else {
                        				$fre[$ymd] = ['begin' => floatval($value[1]), 'end' => floatval($value[2]), 'max' => floatval($value[3]), 'min' => floatval($value[4])];
                    				}
                    			}
                    		}
                    	}
                    }
                }
            }
            return $fre;
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    //推荐数据,每天执行一次
    //yii hello/re
    public function actionRe($limit1 = 30, $limit = 30, $downRate = 0, $addLeast = 500) {
        $data = GuChange1::find()->select('code,z_j_c,rate')->orderBy('current_date_ desc,z_j_c desc,rate asc,')->limit(GuFix::find()->count())->asArray()->all();
        $i = 0;
        $overlap = [];
        foreach ($data as $change) {
        	//增仓、下降
        	$code = $change['code'];
        	$rate = $change['rate'];
        	$zjc = $change['z_j_c'];
        	if ($rate <= $downRate && $zjc > $addLeast) {
        	    $i++;
        	    if ($i <= $limit1) {
            	    $fix = GuFix::findOne(['code' => $code]);
            	    $leftNum = '';
            	    $fiveDay = '';
            	    $name = '';
            	    if ($fix) {
            	        $name = $fix->name;
            	    	$leftNum = round($fix->left_num / 100000000, 3) . '亿';
            	    	$fiveDay = $fix->zjc_five_day;
            	    }
            	    echo sprintf("%-12s|%-6s|%-6s|%-8s|%10s%s", $name, $code, $rate, $zjc, $leftNum, $fiveDay) . PHP_EOL;
        	    }
        	    $overlap[] = $code;
        	}
        }

        $lapStr = [];
        echo '------------------------------------------------------------------------' . PHP_EOL;
        $data = GuFix::find()->select('code,name,left_num,zjc_five_day,rate1,rate3,rate5')->orderBy('rate1 desc,left_num desc, rate3 desc,rate5 desc')->asArray()->all();
        $i = 0;
        foreach ($data as $fix) {
            if ($fix['rate1'] <= $fix['rate3'] &&  $fix['rate3'] <= $fix['rate5']) {
                $i++;
                $str = sprintf("%-12s|%-6s|%-6s|%-6s|%-6s%s", $fix['name'], $fix['code'], $fix['rate1'], $fix['rate3'], $fix['rate5'], $fix['zjc_five_day']) . PHP_EOL;
                if ($i <= $limit) {
                	echo $str;
                }
                if (in_array($fix['code'], $overlap) && ($i <= (3 * $limit))) {
                    $lapStr[$fix['code']] = "\033[0;36m!!!\x1B[0m" . $str;
                }
            }
        }

        echo '------------------------------------------------------------------------' . PHP_EOL;
        $i = 0;
        foreach ($data as $fix) {
            if ($fix['rate1'] > 0.2 || $fix['rate3'] > 0.5 || $fix['rate5'] > 1) {
                $i++;
                $str = sprintf("%-12s|%-6s|%-6s|%-6s|%-6s%s", $fix['name'], $fix['code'], $fix['rate1'], $fix['rate3'], $fix['rate5'], $fix['zjc_five_day']) . PHP_EOL;
                if ($i <= $limit) {
                    echo $str;
                }
            }
        }

        echo '------------------------------------------------------------------------' . PHP_EOL;
        foreach ($lapStr as $str) {
        	echo $str;
        }
    }
}
