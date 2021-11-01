<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Benchmark Class
 *
 * This class enables you to mark points and calculate the time difference
 * between them. Memory consumption can also be displayed.
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Libraries
 * @author        EllisLab Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/benchmark.html
 *
 * @example
 * $this->benchmark->mark('start');
 * ....
 * $this->benchmark->mark('start', 'end');
 */
class POP_Benchmark extends CI_Benchmark
{
    /**
     * List of all benchmark markers
     *
     * @var    array
     */
    public $marker = array();

    /**
     * Set a benchmark marker
     *
     * Multiple calls to this function can be made so that several
     * execution points can be timed.
     *
     * @param    string $name Marker name
     * @return    void
     */
    public function mark($start)
    {
        if (BENCHMARK) {
            $this->marker[$start] = microtime(TRUE);
            // ajax的写文件
            if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
                if (substr($start, -3) === 'End') {
                    $_start = substr($start, 0, -3);
                    $time = $this->elapsed_time($_start, $start);
                    $content = $_start . ' 到 ' . $start . ' 执行时间：' . $time . ' s' . "\n";
                    if ($_start == 'action') {
                        $content .= "\n";
                    }
                    $filename = 'ajax_' . date('Y-m-d') . '.txt';
                    $logPath = dirname(getenv('BASEPATH')) . '/pop136_yuntu/logs/';
                    $file = $logPath . $filename;
                    if ($content) {
                        file_put_contents($file, $content, FILE_APPEND);
                    }
                }
            } // 否则直接输出
            else {
                if (substr($start, -3) === 'End') {
                    $_start = substr($start, 0, -3);
                    $time = $this->elapsed_time($_start, $start);
                    echo '<!--' . $_start . '到' . $start . '执行时间：' . $time . ' s -->' . PHP_EOL;
                }
            }
        }
    }

}
