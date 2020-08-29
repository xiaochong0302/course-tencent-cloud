<?php

namespace App\Services;

class LiveNotify extends Service
{

    public function handle()
    {
        $time = $this->request->getPost('t');
        $sign = $this->request->getPost('sign');
        $type = $this->request->getQuery('action');

        if (!$this->checkSign($time, $sign)) {
            return false;
        }

        switch ($type) {
            case 'streamBegin':
                $result = $this->streamBegin();
                break;
            case 'streamEnd':
                $result = $this->streamEnd();
                break;
            case 'record':
                $result = $this->record();
                break;
            case 'snapshot':
                $result = $this->snapshot();
                break;
            case 'porn':
                $result = $this->porn();
                break;
            default:
                $result = false;
                break;
        }

        return $result;
    }

    /**
     * 推流
     */
    protected function streamBegin()
    {

    }

    /**
     * 断流
     */
    protected function streamEnd()
    {

    }

    /**
     * 断流
     */
    protected function record()
    {

    }

    /**
     * 断流
     */
    protected function snapshot()
    {

    }

    /**
     * 断流
     */
    protected function porn()
    {

    }

    /**
     * 检查签名
     * @param string $sign
     * @param int $time
     * @return bool
     */
    protected function checkSign($sign, $time)
    {
        if (empty($sign) || empty($time)) {
            return false;
        }

        if ($time < time() + 600) {
            return false;
        }

        $live = $this->getSectionSettings('live');

        $mySign = md5($live['notify_auth_key'] . $time);

        return $sign == $mySign;
    }

}