<?PHP
class SPP_Proxy extends SPP_Object {
    /**
     * 当前对象实例
     *
     * @var Object
     */
    public $mObject = null;
    /**
     * 当前用户
     *
     * @var Object
     */
    public $mCurrentUser = null;
    public function __construct($pClassInstance, $pUser) {
        $this->mObject = $pClassInstance;
        $this->mCurrentUser = $pUser;
    }
    /**
     * 方法调用
     *
     * @param String $method            
     * @param array $parameters            
     * @throws Exception
     * @return mixed
     */
    protected function Invoke($method, $parameters) {
        if (true) {
            return call_user_func_array(array($this->mObject,$method ), $parameters);
        } else {
            throw new Exception("权限不足");
        }
    }
    /**
     * __set实现
     *
     * @param string $member            
     * @param mixed $value            
     */
    function __set($member, $value) {
        $this->mObject->$member = $value;
    }
    /**
     * __get实现
     *
     * @param string $member            
     */
    function __get($member) {
        return $this->mObject->$member;
    }
    /**
     * __call实现
     *
     * @param string $method            
     * @param array $paraments            
     * @return mixed
     */
    function __call($method, $paraments) {
        return $this->Invoke($method, $paraments);
    }
}
