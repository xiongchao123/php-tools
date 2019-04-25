<?php

namespace Tool\Algorithm;

/**
 * 使用 snowflake 算法生成递增的分布式唯一ID.
 * 该算法支持 15 条业务线，4 个数据中心，每个数据中心最多 128 台机器，每台机器每毫秒可生成 4096 个不重复ID.
 */
class Snowflake
{
    const SEQUENCE_BITS = 12;
    const MILLISECOND_BITS = 39;
    const BUSINESS_ID_BITS = 4;
    const DATA_CENTER_ID_BITS = 2;
    const MACHINE_ID_BITS = 7;
    const TWEPOC = 1551874309000;
    /**
     * @var int 7bit 毫秒内序列号
     */
    protected $sequence;
    /**
     * @var int 39bit 毫秒数,最后一次生成的时间戳
     */
    protected $last_timestamp;
    /**
     * @var int 4bit 业务线标识
     */
    protected $business_id;
    /**
     * @var int 2bit 机房标识,仅支持4个数据中心
     */
    protected $data_center_id;
    /**
     * @var int 7bit 机器标识，
     */
    protected $machine_id;

    /**
     * Snowflake constructor.
     * @param int $business_id 业务线标识，必须大于0小于等于15
     * @param int $data_center_id 数据中心标识，必须大于0小于等于4
     * @param int $machine_id 机器标识，必须大于0小于等于128
     * @param int $sequence 顺序号，必须大于等于0小于等于4096
     * @throws \Exception
     */
    public function __construct($business_id, $data_center_id, $machine_id, $sequence = 0)
    {
        if ($business_id <= 0 || $business_id > $this->maxBusinessId()) {
            throw new \Exception('Business Id can\'t be greater than 15 or less than 0');
        }
        if ($data_center_id <= 0 || $data_center_id > $this->maxDataCenterId()) {
            throw new \Exception('DataCenter Id can\'t be greater than 4 or less than 0');
        }
        if ($machine_id <= 0 || $machine_id > $this->maxMachineId()) {
            throw new \Exception('Machine Id can\'t be greater than 128 or less than 0');
        }
        if ($sequence < 0 || $sequence > $this->maxSequence()) {
            throw new \Exception('Sequence can\'t be greater than 4096 or less than 0');
        }
        $this->business_id = $business_id;
        $this->data_center_id = $data_center_id;
        $this->business_id = $business_id;
        $this->sequence = $sequence;
    }

    /**
     * @return float 获取当前毫秒数
     */
    public function getTimestamp()
    {
        return floor(microtime(true) * 1000);
    }

    protected function nextMillisecond($lastTimestamp)
    {
        $timestamp = $this->getTimestamp();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->getTimestamp();
        }
        return $timestamp;
    }

    private function maxMachineId()
    {
        return -1 ^ (-1 << self::MACHINE_ID_BITS);
    }

    private function maxDataCenterId()
    {
        return -1 ^ (-1 << self::DATA_CENTER_ID_BITS);
    }

    private function maxBusinessId()
    {
        return -1 ^ (-1 << self::BUSINESS_ID_BITS);
    }

    private function maxSequence()
    {
        return -1 ^ (-1 << self::SEQUENCE_BITS);
    }

    private function mintId64($timestamp, $business_id, $datacenterId, $machine_id, $sequence)
    {
        return (string)$timestamp | $business_id | $datacenterId | $machine_id | $sequence;
    }

    private function timestampLeftShift()
    {
        return self::SEQUENCE_BITS + self::MACHINE_ID_BITS + self::DATA_CENTER_ID_BITS + self::BUSINESS_ID_BITS;
    }

    private function businessIdLeftShift()
    {
        return self::SEQUENCE_BITS + self::MACHINE_ID_BITS + self::DATA_CENTER_ID_BITS;
    }

    private function dataCenterIdShift()
    {
        return self::SEQUENCE_BITS + self::MACHINE_ID_BITS;
    }

    private function machineIdShift()
    {
        return self::SEQUENCE_BITS;
    }

    protected function nextSequence()
    {
        return $this->sequence++;
    }

    public function getLastTimestamp()
    {
        return $this->last_timestamp;
    }

    public function getDataCenterId()
    {
        return $this->data_center_id;
    }

    public function getMachineId()
    {
        return $this->machine_id;
    }

    public function getBusinessId()
    {
        return $this->business_id;
    }

    public function getNextId()
    {
        $timestamp = $this->getTimestamp();
        if ($timestamp < $this->last_timestamp) {
            throw new Exception(sprintf("Clock moved backwards. Refusing to generate id for %d milliseconds", ($this->last_timestamp - $timestamp)));
        }
        if ($timestamp == $this->last_timestamp) {
            $sequence = $this->nextSequence() & $this->maxSequence();
            // sequence rollover, wait til next millisecond
            if ($sequence == 0) {
                $timestamp = $this->nextMillisecond($this->last_timestamp);
            }
        } else {
            $this->sequence = 0;
            $sequence = $this->nextSequence();
        }
        $this->last_timestamp = $timestamp;
        $t = floor($timestamp - self::TWEPOC) << $this->timestampLeftShift();
        $b = $this->getBusinessId() << $this->machineIdShift();
        $dc = $this->getDataCenterId() << $this->dataCenterIdShift();
        $worker = $this->getMachineId() << $this->machineIdShift();
        return $this->mintId64($t, $b, $dc, $worker, $sequence);
    }
}