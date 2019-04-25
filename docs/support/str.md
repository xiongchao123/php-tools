## description

### angle convert

```php
    $fullStr = '０１【demo';
    $semiangleStr = '01[demo';
     // full to semiangle
    echo Str::angleConvert($fullStr,0).PHP_EOL;
    
    // semiangle to full
     echo Str::angleConvert(semiangle,1).PHP_EOL;
```

### word count

```php
   $word = '下一步，鄞州区白鹤街道将启动“两路两侧”靓化行动，在恢复清爽环境的甬台温铁路沿线（鄞州黄鹂社区段）补种绿化，进一步提升城市品位和“颜值”，改善周边居民的生活环境。??';
   
   $count = Str::getWordCount($word);
   
   echo "count : ".$count.PHP_EOL;
```