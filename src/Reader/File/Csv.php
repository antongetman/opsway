<?php

namespace OpsWay\Migration\Reader\File;

    
use OpsWay\Migration\Reader\ReaderInterface;

class Csv implements ReaderInterface
{
    
    protected $file;
    protected $filename;
    protected $itemColumns;
    

    public function __construct(array $configParams)
    {
        $this->filename = $configParams['filename'];
        $this->initFileAndItemColumns();
    }

    /**
     * @return array|null
     */
    public function read()
    {
        $itemData = fgetcsv($this->file);
        if (!$itemData) return null;
        if (count($itemData) != count($this->itemColumns)) { //
            throw new \RuntimeException(sprintf('Incorrect csv line.', $this->filename));
        }
        return array_combine($this->itemColumns,$itemData);//TASK 6, return array with keys column which use csv in header and values
    }
    
    private function initFileAndItemColumns()
    {
        if (!file_exists($this->filename)) {
            throw new \RuntimeException(sprintf('File "%s" does not  exists.', $this->filename));
        }
        if (!($this->file = fopen($this->filename, 'r'))) {
            throw new \RuntimeException(sprintf('Can not read file "%s".', $this->filename));
        }
        $this->itemColumns = fgetcsv($this->file); //TASK 6, get keys column which use csv in header
    }
    
    public function __destruct()
    {
        if ($this->file) {
            fclose($this->file);
        }
    }
    
}
