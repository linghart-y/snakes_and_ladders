<?php

namespace App;

class Game
{
    private $current;

    function __construct($current)
    {
        $this->current = $current;
    }

    private function newDice()
    {
        $roll_result = rand(1,6);
        $modifier = 0;
        $modifier_string = '';

        if(in_array($this->current + $roll_result, [25, 55])){
            $modifier += 10;
            $modifier_string = 'ladder';
        }

        if(($this->current + $roll_result) % 9 === 0){
            $modifier_string = 'snake';
            $modifier -= 3;
        }

        if($this->current >= 94 && $roll_result + $this->current !== 100){
            return $roll_result . ' - ' . $this->current;
        }

        $this->current += $roll_result + $modifier;

        return $roll_result . ' - ' . $modifier_string . $this->current;
    }

    public function start($mode)
    {
        if($mode === 'cli')
        {
            while ($this->current !== 100){
                $result = $this->newDice($this->current);
                echo $result . PHP_EOL;
                sleep(1);
            }
        } else {
            $result = $this->newDice($this->current);
            return ['result'=>$result, 'current'=>$this->current];
        }
    }
}

?>