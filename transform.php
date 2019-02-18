<?php
class Converter
{
	public $length, $level, $suitable, $begin_string, $end_string, $dict, $result, $reverse, $has_result;
	
	function __construct()
	{
		global $argv;
		$this->level = 0;
		$this->reverse = false;
		$this->has_result = false;
		$this->suitable=[];
		$this->result = [];
		$this->begin_string = mb_strtolower($argv[1]);
		$this->end_string = mb_strtolower($argv[2]);
		$this->length = mb_strlen($this->begin_string);
		$this->dict=explode(PHP_EOL, file_get_contents('word_rus.txt'));
		foreach($this->dict as $index => $word){
			if(mb_strlen(trim($word)) !== $this->length){
				unset($this->dict[$index]);
			} else {
				$this->dict[$index] = mb_strtolower(trim($word));
			}
		}
		$this->dict = array_values($this->dict);
	}

	public function search($first,$niddle)
	{
		$i = 0;
		foreach ($this->dict as $key=>$val) 
		{
		    if($this->match($val,$first) == $this->length-1 && $this->match($val,$this->begin_string)>=$this->level) 
		    {
		    	if ($this->match($val,$this->begin_string)-1>=$this->level) $this->level++;	
	    		if ($val !== $niddle)
	    		{
	    			$this->result[] =  $first . ' >> ' . $val . PHP_EOL;
		    		$i++;
		    		if (trim($val) == trim($this->begin_string)){
		    			$this->has_result = true;
		    		}
		    		array_splice($this->dict, $key, 1);
		    		$this->search($val,$first);
	    		}
    		} else {
    			continue;
    		}
		}
		if($i == 0){return;}
	}

	public function match($val1,$val2)
	{
		$result = 0;
		for($i = 0; $i < $this->length; $i++){
			if(mb_substr($val1, $i, 1)==mb_substr($val2, $i, 1)){
				$result++;
			}
		}
		return $result;
	}

	public function check_reverse()
	{
		if($this->has_result == false)
		{
			if($this->reverse == null)
			{
				$this->level = 0;
				$this->reverse = false;
				$this->has_result = false;
				$this->suitable=[];
				$this->result = [];
				$this->reverse = true;
				$tmp = $this->begin_string;
				$this->begin_string = $this->end_string;
				$this->end_string = $tmp;
				$this->search($this->end_string,'');	
			}
		}
		$this->result = $this->result;
		$this->checkResult();
	}

	public function checkResult()
	{
		if($this->has_result){
			foreach($this->reverse ? $this->result : array_reverse($this->result) as $index => $item)
			{
				echo $item;
			}

		} else {
			if($this->reverse){
				echo 'Невозможно конвертировать';
			} else {
				$this->check_reverse();
			}
		}
	}
}

$converter = new Converter();
$converter->search($converter->end_string,'');
$converter->checkResult();
?> 
