<?php

namespace Tool\Algorithm;

class WeightCalculator
{
    /**
     * such as ['A'=>50,'B'=>25,'c'=>25]
     * @var array
     */
    protected $weightValues = array();

    protected $weightSum = 0;

    // 小数点后位数
    protected $point = 0;

    public function setWeightValues(array $weightValues)
    {
        array_multisort($weightValues, SORT_ASC);

        foreach ($weightValues as $key => $value) {
            $tempValue = abs($value);
            $weightValues[$key] = $tempValue;
            if (is_float($tempValue)) {
                $point = $this->getFloatLength($tempValue);
                $this->point = $point <= $this->point ?: $point;
            }

            $this->weightSum += $tempValue;
        }

        if ($this->point) {
            $this->weightSum *= pow(10, $this->point);
        }

        $this->weightValues = $weightValues;
    }

    /**
     * @return int|string|null
     */
    public function getRandomKey()
    {
        if ($this->weightSum < 1) {
            return null;
        }

        $rand = random_int(1, $this->weightSum);
        $max = 0;

        foreach ($this->weightValues as $key => $value) {
            if ($this->point) {
                $max = $max + $value * pow(10, $this->point);
            } else {
                $max += $value;
            }

            if ($rand <= $max) {
                return $key;
            }
        }
    }

    /**
     * @param $num
     * @return int
     */
    private function getFloatLength($num)
    {
        $count = 0;

        $temp = explode('.', $num);

        if (count($temp) > 1) {
            $decimal = end($temp);
            $count = strlen($decimal);
        }

        return $count;
    }
}
