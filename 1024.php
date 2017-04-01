<?php
ini_set("memory_limit", "1024M");
require dirname(__FILE__) . '/../core/init.php';
require_once dirname(__FILE__) . '/../demo/curls.php';
/* Do NOT delete this comment */
/* 不要删除这段注释 */


$configs = array(
    'name' => '1024',
    'tasknum' => 20,
    'domains' => array(
        't66y.com'
    ),
    'scan_urls' => array(
        'http://t66y.com/thread0806.php?fid=16'
    ),
    'content_url_regexes' => array(
        "http://t66y.com/htm_data/[1-9]\d*/[1-9]\d*/[1-9]\d*.html"
    ),
    'list_url_regexes' => array(
        "http://t66y.com/thread0806.php?fid=16&search=&page=[1-9]\d*"
    ),
    'user_agents' => array(
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G34 Safari/601.1",
        "Mozilla/5.0 (Linux; U; Android 6.0.1;zh_cn; Le X820 Build/FEXCNFN5801507014S) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/49.0.0.0 Mobile Safari/537.36 EUI Browser/5.8.015S",
    ),
    'fields' => array(
        array(
            // 抽取内容页的文章标题
            'name' => "title",
            'selector_type' => 'regex',
            'selector' => "@h4>(.*?)</h4@",
            'required' => true
        ),
        array(
//            获取图片地址
            'name' => "img_url",
            'selector_type' => 'regex',
            'selector' => '@(input.*?.(jpg|png|jpeg))@',
            'required' => true,
            'repeated' => true
        )

    ),
    'max_try' => 5,
    'max_try' => 1,
    'log_show' => 'off',
    'log_file' => 'D:/1024img/1025.log',
    'proxy' => 'http://127.0.0.1:1080',
    'export' => array(
        'type' => 'db',
        'table' => '1024',)
);
$spider = new phpspider($configs);
$spider->on_start = function ($phpspider) {
    for ($i = 1; $i < 262; $i++) {
        $phpspider->add_scan_url("http://t66y.com/thread0806.php?fid=16&search=&page={$i}");
    }
};

$spider->on_download_page = function ($page, $phpspider) {
    file_put_contents("D:/1024img/pages/" . uniqid() . ".html", $page['raw']);
    return $page;
};

$spider->on_extract_page = function ($page, $data) {

    $imgs_url = $data['img_url'];
    $title = $data['title'];
    $title = iconv("utf-8", "gbk", $title);
    $imgfloder = "D:/1024/" . $title . "/";
//
    if (!file_exists($imgfloder)) {
        mkdir($imgfloder);
    }
    foreach ($imgs_url[0] as $num => $img) {

        $img = str_replace("input src=\"", "", $img);
        $img = str_replace('input src=\'', "", $img);
        $name = $num + 1;
        $filename = $name . '.jpg';
        $imgpath = $imgfloder . "/" . $filename;
        if (file_exists($imgpath)) {
            if (filesize($imgpath) < 100) {
                $imgdata = curls(stripcslashes("{$img}"));
                file_put_contents($imgpath, $imgdata);
            }
        } else {
            $imgdata = curls(stripcslashes("{$img}"));
            if ($img != null) {
                DB::insert('1024', array(
                    'title' => iconv("gbk", "utf-8", $imgpath),
                    'img_url' => $img));
                file_put_contents($imgpath, $imgdata);
            }
        }

//        $imgdata = curls(stripcslashes("{$img}"));
//        DB::insert('1024', array(
//            'title' => iconv("gbk", "utf-8", $imgpath),
//            'img_url' => $img));
//        file_put_contents($imgpath, $imgdata);


    }

    return $data;
};


$spider->start();