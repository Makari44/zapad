<?php
//========= Задаем параметры капчи(при желании можно вынести в отдельный файл - config)
// шрифт символов на капче( шрифт лучше положить в папку со скриптом)
$font = 'ariblk.ttf'; 
// Размер шрифта 
$fontsize = 22; 
// Размер капчи
$width = 120; // - длина
$height = 40; // - высота
// количество полосок на капче
$countLine = 0;
//==========
  
// Задаем заголовок для вывода картинки
header('Content-type: image/png');  
// Создаем изображение
$img = imagecreatetruecolor($width, $height);  
// фон для капчи
$white = imagecolorallocate($img, 255, 255, 255); 
imagefill($img, 0, 0, $white); 
// Переменная, для хранения значения капчи
$capchaText = ''; 
 
// придумываем пример для капчи
$a = mt_rand(10, 99);
$b = mt_rand(10, 99);
$capchaText = $a . '' . $b . '';
// Ответ на пример, запишем в сессию для проверки
$capchaResult = $capchaText;
 
// ========= Заполням изображение символами
for ($i = 0; $i < strlen($capchaText); $i++){ 
    // Из списка символов, берем случайный символ 
    $litteral = $capchaText[$i];  
    // Вычесляем положение одного символа
    $x = ($width - 20) / strlen($capchaText) * $i + 10; 
    $y = $height - (($height - $fontsize) / 2);     
    // Сгенерируем случайный цвет для символа. 
    $color = imagecolorallocate($img, rand(0, 150), rand(0, 150), rand(0, 150) );  
    // Генерируем угол наклона символа 
    $naklon = rand(-15, 15); 
    // Рисуем один символ
    imagettftext($img, $fontsize, $naklon, $x, $y, $color, $font, $litteral); 
}
// ==========
 
// ========== Добавим на капчу несколько рандомных полосок
for ($i = 0; $i < $countLine; $i++){ 
    // сгенерируем координаты для линии
    $part = $width/100; // длина картинки в процентах
    $x1 = mt_rand(0, round($part*30)); // x1 не больше чем до 30% картики
    $y1 = mt_rand(0, $height);
    $x2 = mt_rand(round($part*70), round($part*100)); // x2 не меньше чем от 70% картики
    $y2 = mt_rand(0, $height);
    // сгенерируем случайный цвет для линии
    $color = imagecolorallocate($img, rand(0, 150), rand(0, 150), rand(0, 150) );  
    imageline ($img, $x1, $y1, $x2, $y2, $color );
}
// ==========
  
// Запускаем сессию, и записывем в нее значение капчи. Это понадобится для проверки с тем, что вводит юзер
session_start(); 
$_SESSION['capcha'] = $capchaResult; 
// вывод капчи на страницу
imagepng($img);  
// чистим память, корторую мы заняли при создании картинки
imagedestroy($img);
?>