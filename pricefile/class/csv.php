<?php
/*
 * @author Damien Legrand
 * www.damienlegrand.com
 */

class CSV {
	
	var $delimiter;
	var $delimiter_row;
	
	var $hasHeader;
	
	var $csv;
	
	/**
	 * Class Constructor
	 * @param char $delimiter comma by default
	 * @param char $delimiter_row line break by default \n
	 * @param bool $hasHeader true if the first line of the csv is the columns names
	 */
	public function __construct($delimiter=',', $enclose='"', $delimiter_row="\n", $hasHeader=true)
	{
		$this->CSV($delimiter, $enclose, $delimiter_row, $hasHeader);
	}
	public function CSV($delimiter=',', $enclose='"', $delimiter_row="\n", $hasHeader=true)
	{
		$this->delimiter = $delimiter;
		$this->delimiter_row = $delimiter_row;
		$this->hasHeader = $hasHeader;
		$this->csv = null;
		$this->enclose = $enclose;
	}
	
	// <editor-fold defaultstate="collapsed" desc="Setter">
	
	/**
	 * Set the delimiter of the CSV, coma by default
	 * @param type $delimiter the delimiter
	 */
	public function SetDelimiter($delimiter)
	{
		if(isset($delimiter))
			$this->delimiter = $delimiter;
	}
	
	/**
	 * Set the row delimiter generaly a line break "\n"
	 * @param string $delimiter_row 
	 */
	public function SetRowDelimiter($delimiter_row)
	{
		if(isset($delimiter_row))
			$this->delimiter = $delimiter_row;
	}
	
	/**
	 * Tel the class if the first line of the CSV is the list of columns names
	 * @param boolean $bool 
	 */
	public function CsvHasHeader($bool)
	{
		if(is_bool($bool))
			$this->hasHeader = $bool;
	}
	
	/**
	 * Set the csv with a string
	 * @param type $string csv as string
	 */
	public function SetCsvFromString($string)
	{
		if(isset($string) && strlen($string) > 0)
		{
			$this->csv = array();
			$this->_prep_array($string);
		}
	}
	
	/**
	 * Set the csv with a csv file
	 * @param string $path csv file name
	 */
	public function SetCsvFromFile($path)
	{
		if(isset($path) && strlen($path) > 0)
		{
			$this->csv = array();
			$this->_prep_array(file_get_contents($path));
		}
	}
	
	/**
	 * Add more data to the current csv from a csv file
	 * @param string $string csv as string
	 */
	public function AddToCsvFromString($string)
	{
		if(is_null($this->csv))
			$this->SetCsvFromString($string);
		else
		{
			if(isset($string) && strlen($string) > 0)
			{
				$this->_prep_array($string);
			}
		}
	}
	
	/**
	 *Add more data to the current csv from a csv file
	 * @param string $path the name file
	 */
	public function AddToCsvFromFile($path)
	{
		if(is_null($this->csv))
			$this->SetCsvFromFile($path);
		else
		{
			if(isset($path) && strlen($path) > 0)
			{
				$this->_prep_array(file_get_contents($path));
			}
		}
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="Getter">
	
	/**
	 * Get the csv as an array
	 * @return array 
	 */
	public function GetArray()
	{
		return $this->csv;
	}
	
	/**
	 * Get an html table with values from csv
	 * @param string $attrs attribus in table balise ( id class ... )
	 * @return string HTML Table
	 */
	public function GetHtmlTable($attrs='')
	{
		$table = null;
		if (!is_null($this->csv))
		{
			$table = "<table $attrs>";

			//Make a Header
			if ($this->hasHeader)
			{
				$table .= "<thead><tr>";
				foreach ($this->csv[0] as $row)
				{
					$table .= "<th>$row</th>";
				}
				$table .= "</tr></thead>";
			}

			//Make content
			$table .= "<tbody>";
			
			$first = true;
			foreach ($this->csv as $row)
			{
				$ok = true;
				if($first)
				{
					$first = false;
					if($this->hasHeader)
						$ok = false;
				}
				
				if($ok)
				{
					$table .= "<tr>";
					
					foreach($row as $col)
					{
						$table .= "<td>$col</td>";
					}
					
					$table .= "</tr>";
				}
			}
			
			$table .= "</tbody>";
			$table .= "</table>";
		}
		
		return $table;
	}
	
	/**
	 * Return the number of row without the header
	 * @return int
	 */
	public function GetRowNumber()
	{
		if(!is_array($this->csv))
			return 0;
		else
		{
			$nb = count($this->csv);
			return ($this->hasHeader)? $nb-1 : $nb;
		}
		
	}
	
	/**
	 * Return the number of column
	 * @return int 
	 */
	public function GetColumnNumber()
	{
		if(!is_array($this->csv))
			return 0;
		else
		{
			return count($this->csv[0]);
		}
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="Private">
	
	private function _prep_array($string)
	{
		//$data = str_getcsv($string, "\n");
		$data = explode($this->delimiter_row, $string);
// 		if ($this->hasHeader)

		foreach($data as $line)
		{
			// Left to do is to make it work with multi delimiter
// 			$line = '"my <a href="string">string<a>";"my <span style="color:black;">second</span> string";one.jpg,two.jpg;"three.jpg,four.jpg"';
			$lineArray = str_split($line);
			$csvArray = array();
			$string = '';
			//"my "string";"my second string"
			$startpos = 0;
			foreach($lineArray as $pos=>$char) {
				if ($char == $this->enclose) // is char is enclose char
					if (!$is_enclosed) // if not set, it's the beginging of string
						$is_enclosed = true;
					else // if it's set, it might be end of string
						if($lineArray[$pos+1] == $this->delimiter) // if char after current is delimter
							$is_enclosed = false; // it is end of string

				if ($char == $this->delimiter) // char is delimiter
					if ($is_enclosed) // string is enclosed, and should not be cut
						$string .= $char;
					else
					{
					// string is not enclosed, cut, and reset string
						$csvArray[] = $string;
						$string = '';
						$startpos = $pos+1;
					}
				elseif ($char == ',')
				{
					if ($is_enclosed) // string is enclosed, and should not be cut
						$string .= $char;
					else
					{
						/*
							best way would be to get the pos of string start char
							and this else should set a bool and save a pos.
							wait until delimiter is next char.
							And then use that to cut up the string.
						*/
						$string .= $char;
// 						$csvArray[] = $string;
// 						$string = '';
					}
				}
				else
					$string .= $char;
			}
			if (strlen($string) > 0)
				$csvArray[] = $string; // whatever is left after last delimiter is added to last element

			$this->csv[] = $csvArray;
		}
	}
	
	// </editor-fold>
}