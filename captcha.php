<?php
//========= ������ ��������� �����(��� ������� ����� ������� � ��������� ���� - config)
// ����� �������� �� �����( ����� ����� �������� � ����� �� ��������)
$font = 'ariblk.ttf'; 
// ������ ������ 
$fontsize = 22; 
// ������ �����
$width = 120; // - �����
$height = 40; // - ������
// ���������� ������� �� �����
$countLine = 0;
//==========
  
// ������ ��������� ��� ������ ��������
header('Content-type: image/png');  
// ������� �����������
$img = imagecreatetruecolor($width, $height);  
// ��� ��� �����
$white = imagecolorallocate($img, 255, 255, 255); 
imagefill($img, 0, 0, $white); 
// ����������, ��� �������� �������� �����
$capchaText = ''; 
 
// ����������� ������ ��� �����
$a = mt_rand(10, 99);
$b = mt_rand(10, 99);
$capchaText = $a . '' . $b . '';
// ����� �� ������, ������� � ������ ��� ��������
$capchaResult = $capchaText;
 
// ========= �������� ����������� ���������
for ($i = 0; $i < strlen($capchaText); $i++){ 
    // �� ������ ��������, ����� ��������� ������ 
    $litteral = $capchaText[$i];  
    // ��������� ��������� ������ �������
    $x = ($width - 20) / strlen($capchaText) * $i + 10; 
    $y = $height - (($height - $fontsize) / 2);     
    // ����������� ��������� ���� ��� �������. 
    $color = imagecolorallocate($img, rand(0, 150), rand(0, 150), rand(0, 150) );  
    // ���������� ���� ������� ������� 
    $naklon = rand(-15, 15); 
    // ������ ���� ������
    imagettftext($img, $fontsize, $naklon, $x, $y, $color, $font, $litteral); 
}
// ==========
 
// ========== ������� �� ����� ��������� ��������� �������
for ($i = 0; $i < $countLine; $i++){ 
    // ����������� ���������� ��� �����
    $part = $width/100; // ����� �������� � ���������
    $x1 = mt_rand(0, round($part*30)); // x1 �� ������ ��� �� 30% �������
    $y1 = mt_rand(0, $height);
    $x2 = mt_rand(round($part*70), round($part*100)); // x2 �� ������ ��� �� 70% �������
    $y2 = mt_rand(0, $height);
    // ����������� ��������� ���� ��� �����
    $color = imagecolorallocate($img, rand(0, 150), rand(0, 150), rand(0, 150) );  
    imageline ($img, $x1, $y1, $x2, $y2, $color );
}
// ==========
  
// ��������� ������, � ��������� � ��� �������� �����. ��� ����������� ��� �������� � ���, ��� ������ ����
session_start(); 
$_SESSION['capcha'] = $capchaResult; 
// ����� ����� �� ��������
imagepng($img);  
// ������ ������, �������� �� ������ ��� �������� ��������
imagedestroy($img);
?>