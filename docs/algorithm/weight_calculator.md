## Algorithm description

### weight_calculator

```php
     $weight = ['A' => 45.5, 'B' => 20.5, 'C' => 19.5,'D'=>9.5];
     $lib = new WeightCalculator();
     $lib->setWeightValues($weight);

     $aCount = $bCount = $cCount = $dCount =  0;
            for ($i = 0; $i < 1000000; $i++) {
                $key = $lib->getRandomKey();
                switch ($key) {
                    case 'A':
                        $aCount++;
                        break;
                    case 'B':
                        $bCount++;
                        break;
                    case 'C':
                        $cCount++;
                        break;
                    case 'D':
                        $dCount++;
                        break;
                }
            }

     echo "a count : $aCount".PHP_EOL;
     echo "b count : $bCount".PHP_EOL;
     echo "c count : $cCount".PHP_EOL;
     echo "d count : $dCount".PHP_EOL;
 
```