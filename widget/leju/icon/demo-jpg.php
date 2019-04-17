<?php
$string = '乐居认证';
header('Content-type: image/jpeg');
// 查询GD拓展信息
$gdInfo = gd_info();
preg_match('/\d/', $gdInfo['GD Version'], $match);
$gdVerson = $match[0];
if(2 == $gdVerson) {
    $textsize = 15; // 点
} else {
    $textsize = 20; // 像素
}
// 获取字符串宽度
$font = './images/STXIHEI.TTF';
$bbox = imageftbbox($textsize, 0, $font, $string);
// 载入背景图
$imgLeft = imagecreatefromjpeg ('./images/left.jpg');
$imgCenter = imagecreatefromjpeg ('./images/center.jpg');
$imgRight = imagecreatefromjpeg ('./images/right.jpg');
// 计算位置信息
$paddingLeft = 6;
$paddingRight = 3;
$height = imagesy($imgLeft);
$widthLeft = imagesx($imgLeft);
$widthCenter = $bbox[4] + $paddingLeft + $paddingRight;
$widthRight = imagesx($imgRight);
$width = $widthLeft + $widthCenter + $widthRight;
// 创建图像
$img = imagecreatetruecolor($width, $height);
// 透明处理
$color = imagecolorallocate($img, 255, 255, 255);
imagecolortransparent($img, $color);
imagefill($img, 0, 0, $color);
// 拷贝图像
imagecopy($img, $imgLeft, 0, 0, 0, 0, $widthLeft, $height);
for($i = 0; $i < $widthCenter; $i++) {
	imagecopy($img, $imgCenter, $widthLeft + $i, 0, 0, 0, 1, $height);	
}
imagecopy($img, $imgRight, $widthLeft + $widthCenter, 0, 0, 0, $widthRight, $height);
// 绘制文本
$textcolor = imagecolorallocate($img, 255, 255, 255);
imagettftext($img, $textsize, 0, $widthLeft + $paddingLeft, $height / 3 * 2, $textcolor, $font, $string);
// 输出图像
imagejpeg($img);
// 释放资源
imagedestroy($imgLeft);
imagedestroy($imgCenter);
imagedestroy($imgRight);
imagedestroy($img);
