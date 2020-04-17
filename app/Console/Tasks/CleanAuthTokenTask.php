<?php

namespace App\Console\Tasks;

use App\Models\AccessToken as AccessTokenModel;
use App\Models\RefreshToken as RefreshTokenModel;
use Phalcon\Cli\Task;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CleanAuthTokenTask extends Task
{

    public function mainAction()
    {
        $accessTokens = $this->findAccessTokens();

        if ($accessTokens->count() > 0) {
            $accessTokens->delete();
        }

        $refreshTokens = $this->findRefreshTokens();

        if ($refreshTokens->count() > 0) {
            $refreshTokens->delete();
        }
    }

    /**
     * 查找待清理访问令牌
     *
     * @return ResultsetInterface|Resultset|AccessTokenModel[]
     */
    protected function findAccessTokens()
    {
        $expiryTime = strtotime('-30 days');

        return AccessTokenModel::query()
            ->where('expiry_time < :expiry_time:', ['expiry_time' => $expiryTime])
            ->execute();
    }

    /**
     * 查找待清理刷新令牌
     *
     * @return ResultsetInterface|Resultset|RefreshTokenModel[]
     */
    protected function findRefreshTokens()
    {
        $expiryTime = strtotime('-30 days');

        return RefreshTokenModel::query()
            ->where('expiry_time < :expiry_time:', ['expiry_time' => $expiryTime])
            ->execute();
    }

}
