<?php

namespace AlexBrin\Qiwi;

class Qiwi
{
    public const URL = "https://edge.qiwi.com/";

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $token;

    /**
     * Qiwi constructor.
     * @param string $phone
     * @param string $token
     */
    public function __construct(string $phone, string $token)
    {
        $this->phone = $phone;
        $this->token = $token;
    }

    /**
     * Получить информацию об аккаунте
     * @param array $params
     * @throws \Exception
     * @return array
     *
     * params
     *  authInfoEnabled (bool, default true) - загрузка настроек авторизации
     *  contractInfoEnabled (bool, default true) - загрузка данных о кошельке
     *  userInfoEnabled (bool, default true) - загрузка прочих пользовательских данных
     *
     */
    public function getAccount(array $params = []): array {
        $params += [
            'authInfoEnabled' => true,
            'contractInfoEnabled' => true,
            'userInfoEnabled' => true,
        ];
        return $this->request('person-profile/v1/profile/current',  $params);
    }

    /**
     * @param array $params
     * @throws \Exception
     * @return array
     *
     * params
     *  rows (int, default 50) - число платежей в ответе
     *  operation (string, default ALL) - тип операции
     *      ALL - все операции
     *      IN - только пополнения
     *      OUT - только платежи
     *      QIWI_CARD - платежи по картам Qiwi
     */
    public function getPaymentHistory(array $params = []): array {
        $params += [
            'rows' => 50,
        ];
        return $this->request("payment-history/v2/persons/{$this->phone}/payments", $params);
    }

    /**
     * Поиск платежа по комментарию
     * @param string $comment
     * @return array|null
     * @throws \Exception
     */
    public function findPaymentByComment(string $comment): ?array {
        $payments = $this->getPaymentHistory();
        foreach($payments['data'] as $payment) {
            if($payment['comment'] === $comment)
                return $payment;
        }

        return null;
    }

    /**
     * Баланс кошельков Qiwi
     * @return array
     * @throws \Exception
     */
    public function getBalance() {
        return $this->request("funding-sources/v2/persons/{$this->phone}/accounts");
    }

    /**
     * Отправка запроса на Qiwi API
     * @param string $method
     * @param array $params
     * @param bool $post
     * @return array
     * @throws \Exception
     */
    public function request(string $method, array $params = [], bool $post = false): array {
        $ch = curl_init();

        if($post) {
            curl_setopt($ch, CURLOPT_URL, self::URL . $method);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        } else
            curl_setopt($ch, CURLOPT_URL, self::URL . $method . '/?' . http_build_query($params));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
            'Host: edge.qiwi.com',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        if(!$result)
            throw new \Exception("cURL (" . curl_errno($ch) . "): " . curl_error($ch));

        curl_close($ch);

        return json_decode($result, true);
    }

}