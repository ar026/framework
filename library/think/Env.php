<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think;

class Env
{
    /**
     * 环境变量数据
     * @var array
     */
    protected $data = [];

    public function __construct()
    {
        $this->data = $_ENV;
    }

    /**
     * 读取环境变量定义文件
     * @access public
     * @param  string    $file  环境变量定义文件
     * @return void
     */
    public function load($file)
    {
        $env = parse_ini_file($file, true);
        $this->set($env);
    }

    /**
     * 获取环境变量值
     * @access public
     * @param  string    $name 环境变量名（支持二级 .号分割）
     * @param  string    $default  默认值
     * @return mixed
     */
    public function get($name = null, $default = null)
    {
        if (is_null($name)) {
            return $this->data;
        }

        $name = strtoupper($name);

        if (strpos($name, '.')) {
            list($item1, $item2) = explode('.', $name, 2);
            return isset($this->data[$item1][$item2]) ? $this->data[$item1][$item2] : $default;
        } elseif (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }

    /**
     * 设置环境变量值
     * @access public
     * @param  string|array  $env   环境变量
     * @param  string        $value  值
     * @return void
     */
    public function set($env, $value = null)
    {
        if (is_array($env)) {
            $env = array_change_key_case($env, CASE_UPPER);

            foreach ($env as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $this->data[$key][strtoupper($k)] = $v;
                    }
                } else {
                    $this->data[$key] = $val;
                }
            }
        } elseif (strpos($env, '.')) {
            list($item1, $item2)        = explode('.', strtoupper($env), 2);
            $this->data[$item1][$item2] = $value;
        } else {
            $this->data[strtoupper($env)] = $value;
        }
    }
}
