<?php
/**
 * CSV Read and Write Class
 *
 *
 * @package        	PHP
 * @subpackage    	Class, Libraries
 * @category    	Class, Libraries
 * @author        	Achmad Rozikin
 */
class Csvrw {

	
	public $savePath = './temporary/';
	public $separator = ';';

	/**
	 * Write CSV String that can be save to anywhere
	 */
	public function writeCSVString($header, $data){
		$csvString = '';

		if(!is_array($header)) $csvString = $header.PHP_EOL;
		else $csvString = implode($this->separator, $header).PHP_EOL;

		if(!is_array($data)) $csvString .= $data.PHP_EOL;
		else{
			foreach($data as $perData){
				if(!is_array($perData)) $csvString .= $perData.PHP_EOL;
				else $csvString .= implode($this->separator, $perData).PHP_EOL;			
			}
		}

		return $csvString;
	}
	/**
	 * Read CSV String white the given string format
	 */
	public function readCSVString($stringData){

		$rowData = preg_split('/\r\n|\r|\n/', $stringData);// Explode by new line
		$rowData = array_slice($rowData, 0); // Discard the first line

		$i = 0;

		$dataArray = [];
		// Explode by coma
		foreach($rowData as $perRowData){
			if( !empty(trim($perRowData)) ){
				$columnData = explode($this->separator, $perRowData);
				$j = 0;
				$dataSet = [];
				foreach ($columnData as $perColumnData) {
					$perColumnData = preg_replace("/\s+|[[:^print:]]/", "", trim($perColumnData));

					// First header is column name
					if($i == 0){
						$header[$j] = $perColumnData;
					}else{
						if(isset($header[$j])) $dataSet[$header[$j]] = $perColumnData;
						else $dataSet[$j] = $perColumnData;
					}
					$j++;
				}
				if(!empty($dataSet)) $dataArray[] = $dataSet;
				$i++;
			}
		}
		return $dataArray;
	}
	/**
	 * Saving CSV String to file
	 */
	public function writeCSVFile($header, $data, $file_name){

		$csvString = $this->writeCSVString($header, $data);

		$file_name = $file_name.'.csv';
		$csvFullDir = $this->savePath.$file_name;

		if(file_exists($csvFullDir)) unlink($csvFullDir);
		file_put_contents($csvFullDir, $csvString);	

		return $csvFullDir;
	}

}