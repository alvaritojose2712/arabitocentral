<?php 



if (! function_exists('zero_fill')) {
    function zero_fill($val) {
        return sprintf("%08d", $val);
    }
}

if (! function_exists('moneda')) {
    function moneda($val,$num=2) {
        $val = $val ? $val : 0;
        
        return number_format(floatval($val),$num,",",".");
    }
}
if (! function_exists('removemoneda')) {
    function removemoneda($money) {
        
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousandSeparator);
    }
}

if (! function_exists('toLetras')) {
    function toLetras($val)
    {
        $letras = [
            "1"=>"L",
            "2"=>"R",
            "3"=>"E",
            "4"=>"A",
            "5"=>"S",
            "6"=>"G",
            "7"=>"F",
            "8"=>"B",
            "9"=>"P",
            "0"=>"X",
        ];


        foreach ($letras as $numero => $letra) {
            
           $val = str_replace($numero, $letra, $val);
        }

        return $val;
    }
}

  
 ?>