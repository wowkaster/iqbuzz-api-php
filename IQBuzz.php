<?php

include __DIR__.'/interfaces/IQuery.php';
include __DIR__.'/interfaces/IConvert.php';

include __DIR__.'/helpers/ConvertArrayToXml.php';
include __DIR__.'/helpers/ConvertXmlToArray.php';

include __DIR__.'/queries/QueryAbstract.php';
include __DIR__.'/queries/QueryXml.php';
include __DIR__.'/queries/QuerySimple.php';

include __DIR__.'/IQBuzzRequest.php';
include __DIR__.'/IQBuzzResponse.php';


/**
 * Class IQBuzz
 */
class IQBuzz {

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $apiCode;

    private $request;

    /**
     * @var IQuery
     */
    private $queryObject;

    public function __construct($login, $apiCode) {
        $this->login = $login;
        $this->apiCode = $apiCode;

        $this->request = new IQBuzzRequest();
        $this->request->setParam('login', $login);
        $this->request->setParam('apiCode', $apiCode);

    }

    /**
     * @param IQuery $object
     * @return $this
     */
    public function setQueryObject(IQuery $object) {
        $this->queryObject = $object;
    }

    /**
     * @return IQBuzz
     */
    public function getPrepare() {
        $this->request->setUr($this->queryObject->getQueryUrl());
        $this->request->setParam('login', $this->login);
        $this->request->setParam('apiCode', $this->apiCode);

        foreach($this->queryObject->getQueryParams() as $param => $value) {
            $this->request->setParam($param, $value);
        }

        return $this;
    }

    /**
     * @return IQBuzzResponse
     */
    public function getResult() {
        return new IQBuzzResponse($this->request->getResult());
    }

    /**
     * @return array
     */
    public function getDebug() {

        return array(
            'url' =>   $this->queryObject->getQueryUrl(),
            'params' => $this->queryObject->getParams(),
            'queryParams' => array_merge(
                array(
                    'login' => $this->login,
                    'apiCode' => $this->apiCode
                ),
                $this->queryObject->getQueryParams()
            )
        );
    }
}