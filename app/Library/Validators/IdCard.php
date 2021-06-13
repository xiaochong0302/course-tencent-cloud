<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Validators;

class IdCard
{

    /**
     * 验证身份证是否有效
     *
     * @param string $idCard
     * @return bool
     */
    public function validate($idCard)
    {
        if (strlen($idCard) == 18) {

            return $this->check18IdCard($idCard);

        } elseif ((strlen($idCard) == 15)) {

            $idCard = $this->convertIdCard15to18($idCard);

            return $this->check18IdCard($idCard);
        }

        return false;
    }

    /**
     * 计算身份证的最后一位验证码,根据国家标准GB 11643-1999
     *
     * @param string $idCardBody
     * @return bool|mixed
     */
    private function calcIdCardCode($idCardBody)
    {
        if (strlen($idCardBody) != 17) {
            return false;
        }

        /**
         * 加权因子
         */
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        /**
         * 校验码对应值
         */
        $code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        $checksum = 0;

        for ($i = 0; $i < strlen($idCardBody); $i++) {
            $checksum += substr($idCardBody, $i, 1) * $factor[$i];
        }

        return $code[$checksum % 11];
    }

    /**
     * 将15位身份证升级到18位
     *
     * @param string $idCard
     * @return bool|string
     */
    private function convertIdCard15to18($idCard)
    {
        if (strlen($idCard) != 15) {
            return false;
        }

        /**
         * 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
         */
        if (array_search(substr($idCard, 12, 3), array('996', '997', '998', '999')) !== false) {
            $idCard = substr($idCard, 0, 6) . '18' . substr($idCard, 6, 9);
        } else {
            $idCard = substr($idCard, 0, 6) . '19' . substr($idCard, 6, 9);
        }

        $idCard = $idCard . $this->calcIdCardCode($idCard);

        return $idCard;
    }

    /**
     * 18位身份证校验码有效性检查
     *
     * @param string $idCard
     * @return bool
     */
    private function check18IdCard($idCard)
    {
        if (strlen($idCard) != 18) {
            return false;
        }

        $idCardBody = substr($idCard, 0, 17); // 身份证主体
        $idCardCode = strtoupper(substr($idCard, 17, 1)); // 身份证最后一位的验证码

        if ($this->calcIdCardCode($idCardBody) != $idCardCode) {
            return false;
        }

        return true;
    }

}
