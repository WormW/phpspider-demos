<?php
/**
 * Created by PhpStorm.
 * User: wangg
 * Date: 2017/3/30 0030
 * Time: 16:36
 */
function curls($url, $timeout = '10')
{
    // 1. 初始化
    $ch = curl_init();
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.19 Safari/537.36');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//解决下载https文件的问题
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//同上
    curl_setopt($ch,CURLOPT_PROXY,'127.0.0.1');//代理ip 下面是端口
    curl_setopt($ch,CURLOPT_PROXYPORT,'1080');


    // 3. 执行并获取HTML文档内容
    $info = curl_exec($ch);
    // 4. 释放curl句柄
    curl_close($ch);

    return $info;
}
//
// file_put_contents('1.jpg',curls(stripcslashes('http:\/\/img6.uploadhouse.com\/fileuploads\/21242\/21242326a9416f1b8d05380b37354559f99c0489.jpg')));
////file_put_contents('2.jpg',file_get_contents('http:\/\/img6.uploadhouse.com\/fileuploads\/21242\/21242326a9416f1b8d05380b37354559f99c0489.jpg'));