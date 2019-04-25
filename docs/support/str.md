## description

### angle convert

```php
    $fullStr = '������demo';
    $semiangleStr = '01[demo';
     // full to semiangle
    echo Str::angleConvert($fullStr,0).PHP_EOL;
    
    // semiangle to full
     echo Str::angleConvert(semiangle,1).PHP_EOL;
```

### word count

```php
   $word = '��һ����۴�����׺׽ֵ�����������·���ࡱ�����ж����ڻָ���ˬ�������̨����·���ߣ�۴�ݻ�������Σ������̻�����һ����������Ʒλ�͡���ֵ���������ܱ߾�����������??';
   
   $count = Str::getWordCount($word);
   
   echo "count : ".$count.PHP_EOL;
```