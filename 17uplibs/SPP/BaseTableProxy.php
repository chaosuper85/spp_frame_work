<?PHP
/**
 * BaseTable 的代理类
 */
class SPP_BaseTableProxy extends SPP_Proxy implements IteratorAggregate {
    public $mMapHash;
    public function __construct(SPP_BaseTable $pClassInstance, $pUser) {
        parent::__construct($pClassInstance, $pUser);
        $this->mMapHash = $this->mObject->mMapHash;
    }
    function getIterator() {
        return $this->mObject->getIterator();
    }
}
