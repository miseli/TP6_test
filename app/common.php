<?php
// 应用公共文件

require('phpqrcode/qrlib.php');

if (!defined('MY_FILE')) { // 定义一个常量来标记已经被包含的文件
    define('MY_FILE', true);
    
    // 此处放置需要包含的文件路径或内容
	


ini_set("display_errors", "On"); //打开错误提示
ini_set("error_reporting", E_ALL); //显示所有错误
/**
 * @Author: Cube
 * @Date:   2022-03-30 08:36:15
 * @Last Modified by:   Cube
 * @Last Modified time: 2023-09-30 01:11:36
 */

// @header("content-Type: text/html; charset=gbk");
$version="2022.3.30SF";
date_default_timezone_set('Asia/Shanghai') && error_reporting(0);

function _GET($n) { return isset($_GET[$n]) ? $_GET[$n] : NULL; }
function _POST($n) { return isset($_POST[$n]) ? $_POST[$n] : NULL; }

function _SERVER($n) { return isset($_SERVER[$n]) ? $_SERVER[$n] : NULL; }

function memory_usage() { $memory  = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB'; return $memory;}

function micro_time_float() { $mtime = microtime(); $mtime = explode(' ', $mtime); return $mtime[1] + $mtime[0];}

function get_hash() {return sha1(uniqid());}

// 美化打印任何变量
function debugger(...$a) {
    @header("content-Type: text/html; charset=gbk");
    echo '<div style="display:flex;flex-direction: column;"';
    foreach (debug_backtrace() as $item) {
        echo '<span style="">';
        echo '<b>'.$item['function'].'</b> called at line <b>'.$item['line'].'</b> in file: <b>'.$item['file'].'</b><br/>';
        echo '</span>';
    }
    echo "<pre>";

    print_r($a);
    echo "<p></p>";
    var_dump($a);
    echo "</pre>";
    echo '</div>';
}



}

// func [] at line [] in file []
?>