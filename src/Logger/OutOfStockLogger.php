<?php
namespace OpsWay\Migration\Logger;

use OpsWay\Migration\Writer\WriterFactory;

class OutOfStockLogger
{
    static public $countItem = 0;
    static public $csvWriter;
    protected $debug;
    protected $logFilename;

    public function __construct($mode = false, $params = array())
    {
        $this->debug = $mode;
        $this->logFilename = $params['out_of_stock_file'];
    }

    public function __invoke($item, $status, $msg)
    {
        if ($item['qty'] == 0 && $item['is_stock'] == 0) {
            $this->getCsvWriter()->write($item);
        }
    }
    
    /**
     * @return OpsWay\Migration\Writer\File\Csv
     */
    protected function getCsvWriter()
    {
        if (!self::$csvWriter) {
            self::$csvWriter = WriterFactory::create('File\Csv', array('filename' => $this->logFilename));
        }
        return self::$csvWriter;
    }
}
