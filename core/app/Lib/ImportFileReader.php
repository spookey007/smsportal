<?php

namespace App\Lib;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class  ImportFileReader
{

    /**
     * this class basically generated for read data or insert data  from user import file
     * =variants kinds of file read here
     * like csv,xlsx,cs
     */


    /**
     * if data insert mode true then read data from file insert into target model class or db table
     * or insert mode false just return read data from file as array
     */

    public $dataInsertMode = true;


    /**
     * colum name of upload file ,like name,email,mobile,etc
     * colum name must be same of target table colum name
     */
    public $columns = [];

    /**
     * check the value exits on DB: table
     *
     */

    public $uniqueColumns = [];

    /**
     * on upload model class
     */
    public $modelName;

    /**
     * upload file
     */
    public $file;

    /**
     * supported input file extensions
     */
    public $fileSupportedExtension = ['csv', 'xlsx', 'txt'];

    /**
     *Here store all data from read text,csv,excel file

     */

    public $allData = [];

    /**
     *ALL Unique data store here

     */
    public $allUniqueData = [];

    public $notify = [];


    public function __construct($file, $modelName = null)
    {
        $this->file      = $file;
        $this->modelName = $modelName;
    }


    public function readFile()
    {
        $fileExtension = strtolower($this->fileExtension());

        if ($fileExtension == 'csv') {
            return  $this->readCsvFile();
        } elseif ($fileExtension == "xlsx") {
            return $this->readExcelFile();
        } elseif ($fileExtension == 'txt') {
            return $this->readTextFile();
        }
    }

    public function readCsvFile()
    {
        $file       = fopen($this->file, "r");
        $fileHeader = fgetcsv($file);

        $this->validateFileHeader($fileHeader);

        while (!feof($file)) {
            $fileContact = fgetcsv($file);
            $this->dataReadFromFile($fileContact);
        }

        return $this->saveData();
    }

    public function readExcelFile()
    {
        $spreadsheet = IOFactory::load($this->file);
        $data        = $spreadsheet->getActiveSheet()->toArray();

        if (count($data) <= 0) {
            $this->exceptionSet("File can not be empty");
            return 0;
        }
        $this->validateFileHeader(array_filter(@$data[0]));
        unset($data[0]);
        foreach ($data as  $items) {
            $this->dataReadFromFile($items);
        };


        return $this->saveData();
    }

    function readTextFile()
    {

        $fileContents = file_get_contents($this->file);
        $fileContents = explode(PHP_EOL, $fileContents);
        $fileHeader   = explode(',', @$fileContents[0]);

        $this->validateFileHeader($fileHeader);

        unset($fileContents[0]);

        foreach ($fileContents as $content) {
            $this->dataReadFromFile(explode(',', $content));
        }

        return $this->saveData();
    }

    public function fileExtension()
    {
        $fileExtension = $this->file->getClientOriginalExtension();
        if (!in_array($fileExtension, $this->fileSupportedExtension)) {
            $this->exceptionSet("File type not supported");
        }
        return $fileExtension;
    }

    public function validateFileHeader($fileHeader)
    {

        if (!is_array($fileHeader) || count($fileHeader) != count($this->columns)) {
            $this->exceptionSet("Invalid file format");
        }
        foreach ($fileHeader as $k => $header) {
            if (trim(strtolower($header)) != strtolower(@$this->columns[$k])) {
                $this->exceptionSet("Invalid file format");
            }
        }
    }

    public function dataReadFromFile($data)
    {

        if (gettype($data) != 'array') {
            return 0;
        }

        if (count($data) != count($this->columns)) {
            $this->exceptionSet('Invalid data provided');
            return 0;
        }

        if ($this->dataInsertMode && (!$this->uniqueColumCheck($data))) {
            $this->allUniqueData[] = array_combine($this->columns, $data);
        }

        $this->allData[] = $data;
    }

    function uniqueColumCheck($data)
    {

        $combinedData      = array_combine($this->columns, $data);
        $uniqueColumns     = array_intersect($this->uniqueColumns, $this->columns);
        $uniqueColumnCheck = false;

        foreach ($uniqueColumns as $uniqueColumn) {
            $uniqueColumnsValue = $combinedData[$uniqueColumn];

            if ($uniqueColumnsValue && $uniqueColumn) {
                $uniqueColumnCheck = $this->modelName::where($uniqueColumn, $uniqueColumnsValue)->exists();
            }
        }

        return $uniqueColumnCheck;
    }

    public function saveData()
    {
        if (count($this->allUniqueData) > 0 && $this->dataInsertMode) {
            try {
                $this->modelName::insert($this->allUniqueData);
            } catch (Exception $e) {
                $this->exceptionSet('This file can\'t be uploaded. It may contains duplicate data.');
            }
        }

        $this->notify = [
            'success' => true,
            'message' => count($this->allUniqueData) . " data uploaded successfully total " . count($this->allData) . ' data'
        ];
    }

    public function exceptionSet($exception)
    {
        throw new Exception($exception);
    }

    public function getReadData()
    {
        return $this->allData;
    }

    public function notifyMessage()
    {
        $notify = (object) $this->notify;
        return $notify;
    }
}
