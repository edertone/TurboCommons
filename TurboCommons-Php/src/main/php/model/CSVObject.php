<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\model;

use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * CSV data abstraction
 */
class CSVObject extends TableObject{


    /**
     * CSVObject stores all the information for a CSV document and provides easy access to all the
     * columns and values and allows us to operate with it's data easily.
     *
     * @param string $string A string containing valid csv data
     * @param boolean $headers Specifies if the first row of the provided csv data contains the column names or not. It is important to correctly set this value to avoid invalid data
     * @param string $delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param string $enclosure The character that is used to escape fields when special characters are found
     *
     * @throws UnexpectedValueException
     *
     * @return CSVObject The constructed CSVObject
     */
    public function __construct($string = '', $headers = false, $delimiter = ',', $enclosure = '"'){

        if(!StringUtils::isString($string)){

            throw new UnexpectedValueException('CSVObject->constructor expected a string value');
        }

        parent::__construct();

        if(StringUtils::isEmpty($string)){

            return;
        }

        $currentRow = 0;
        $currentColumn = 0;
        $enclosureFound = false;
        $fieldValue = '';
        $stringLen = strlen($string);

        for ($i = 0; $i < $stringLen; $i++) {

            $character = $string[$i];

            if($character === $enclosure){

                if($enclosureFound && substr($string, $i + 1, 1) === $enclosure){

                    $fieldValue .= $character;
                    $i++;

                }else{

                    $enclosureFound = !$enclosureFound;

                    if($enclosureFound){

                        $fieldValue = '';

                    }else{

                        $i = $this->_findNextDelimiterIndex($string, $i, $delimiter, $stringLen) - 1;
                    }
                }

                continue;
            }

            if ($character === $delimiter && !$enclosureFound) {

                $this->_insertField($currentRow, $currentColumn, $fieldValue);

                $fieldValue = '';
                $currentColumn ++;

                continue;
            }

            if($character === "\r" || $character === "\n"){

                if($enclosureFound){

                    $fieldValue .= $character;

                }else{

                    if($currentColumn > 0){

                        $this->_insertField($currentRow, $currentColumn, $fieldValue);

                        $currentRow ++;
                        $fieldValue = '';
                        $currentColumn = 0;
                    }
                }

                if($character === "\r" && substr($string, $i + 1, 1) === "\n"){

                    if($enclosureFound){

                        $fieldValue .= "\n";
                    }

                    $i++;
                }

                continue;
            }

            $fieldValue .= $character;
        }

        if($fieldValue != '' || $currentColumn >= $this->countColumns()){

            $this->_insertField($currentRow, $currentColumn, $fieldValue);
        }

        if($headers){

            $this->setColumnNames($this->getRow(0));
            $this->removeRow(0);
        }
    }


    /**
     * Set the value for a csv cell
     *
     * @param integer $row An integer containing the index for the row that we want to set
     * @param mixed $column An integer or a string containing the index or label for the column that we want to set
     * @param string $value The value we want to set to the specified cell. Only string values are allowed
     *
     * @return mixed The assigned value after beign stored into the csv cell
     */
    public function setCell($row, $column, $value){

        if(!StringUtils::isString($value)){

            throw new UnexpectedValueException('CSVObject->setCell value must be a string');
        }

        return parent::setCell($row, $column, $value);
    }


    /**
     * TODO
     */
    public function toString(){

        // TODO
    }


    /**
     * Auxiliary method that is used to add a new field to the table at the specified position
     *
     * @param integer $currentRow The row where we want to add the field
     * @param integer $currentColumn The column where we want to add the field
     * @param string $fieldValue The value we want to add to the field
     *
     * @return void
     */
    private function _insertField($currentRow, $currentColumn, $fieldValue){

        if ($currentRow >= $this->countRows()){

            $this->addRows(1);
        }

        if ($currentColumn >= $this->countColumns()){

            $this->addColumns(1);
        }

        $this->setCell($currentRow, $currentColumn, $fieldValue);
    }


    /**
     *Auxiliary method that looks for the next delimiter or newline characters on the csv string starting at the specified position.
     *
     * @param string $string The full csv string to search in.
     * @param integer $currentIndex The csv string starting point for the search
     * @param string $delimiter The character that is used as the csv delimiter
     *
     * @return integer The index where the next delimiter or newline character is found
     */
    private function _findNextDelimiterIndex($string, $currentIndex, $delimiter, $stringLen){

        for ($i = $currentIndex + 1; $i < $stringLen; $i++) {

            $char = $string[$i];

            if($char === $delimiter || $char === "\r" || $char === "\n"){

                return $i;
            }
        }

        return $stringLen;
    }
}

?>