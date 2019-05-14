<?php

namespace Tool\Kernel\Coroutine;

/**
 * Class Scheduler
 */
Class Scheduler
{
    /**
     * @var \SplQueue
     */
    protected $taskQueue;
    /**
     * @var int
     */
    protected $tid = 0;

    /**
     * Scheduler constructor.
     */
    public function __construct()
    {
        /* ԭ�����ά����һ�����У�
         * ǰ��˵�����ӱ�̽Ƕ��Ͽ���Э�̵�˼�뱾���Ͼ��ǿ������������ó���yield���ͻָ���resume������
         * */
        $this->taskQueue = new \SplQueue();
    }

    /**
     * ����һ������
     *
     * @param \Generator $task
     * @return int
     */
    public function addTask(\Generator $task)
    {
        $tid = $this->tid;
        $task = new Task($tid, $task);
        $this->taskQueue->enqueue($task);
        $this->tid++;
        return $tid;
    }

    /**
     * ������������
     *
     * @param Task $task
     */
    public function schedule(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }

    /**
     * ���е�����
     */
    public function run()
    {
        while (!$this->taskQueue->isEmpty()) {
            // �������
            $task = $this->taskQueue->dequeue();
            $task->run(); // ��������ֱ�� yield

            if (!$task->isFinished()) {
                $this->schedule($task); // ���������û��ȫִ����ϣ���ӵ��´�ִ��
            }
        }
    }
}