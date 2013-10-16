<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $pattern="/[^а-пр-яА-ПР-Яa-zA-Z0-9\-\s]/";//РрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя
        echo "найдено? - ".preg_match($pattern, "АаБбВиГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя", $matches);
        var_dump($matches);
        ?>
    </body>
</html>
