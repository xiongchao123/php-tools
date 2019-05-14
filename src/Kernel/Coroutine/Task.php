<?php

namespace Tool\Kernel\Coroutine;

/**
 * Task������
 */
class Task
{
    protected $taskId;
    protected $coroutine;
    protected $beforeFirstYield = true;
    protected $sendValue;

    /**
     * Task constructor.
     * @param $taskId
     * @param \Generator $coroutine
     */
    public function __construct($taskId, \Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    /**
     * ��ȡ��ǰ��Task��ID
     *
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * �ж�Taskִ�������û��
     *
     * @return bool
     */
    public function isFinished()
    {
        return !$this->coroutine->valid();
    }

    /**
     * �����´�Ҫ����Э�̵�ֵ������ $id = (yield $xxxx)�����ֵ�͸���$id��
     *
     * @param $value
     */
    public function setSendValue($value)
    {
        $this->sendValue = $value;
    }

    /**
     * ��������
     *
     * @return mixed
     */
    public function run()
    {
        // ����Ҫע�⣬�������Ŀ�ʼ��reset�����Ե�һ��ֵҪ��current��ȡ
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            // ����˵���ˣ���sendȥ����һ��������
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }
}