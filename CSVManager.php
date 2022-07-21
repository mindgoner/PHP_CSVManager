<?php

    class CSVTools{
        // Display splitted string and mask
        public function computeStringDiff($from, $to){
            $from = str_split($from);
            $to = str_split($to);
        
            $diffValues = array();
            $diffMask = array();
        
            $dm = array();
            $n1 = count($from);
            $n2 = count($to);
        
            for ($j = -1; $j < $n2; $j++) $dm[-1][$j] = 0;
            for ($i = -1; $i < $n1; $i++) $dm[$i][-1] = 0;
            for ($i = 0; $i < $n1; $i++){
                for ($j = 0; $j < $n2; $j++){
                    if ($from[$i] == $to[$j]){
                        $ad = $dm[$i - 1][$j - 1];
                        $dm[$i][$j] = $ad + 1;
                    }else{
                        $a1 = $dm[$i - 1][$j];
                        $a2 = $dm[$i][$j - 1];
                        $dm[$i][$j] = max($a1, $a2);
                    }
                }
            }
        
            $i = $n1 - 1;
            $j = $n2 - 1;
            while (($i > -1) || ($j > -1))
            {
                if ($j > -1)
                {
                    if ($dm[$i][$j - 1] == $dm[$i][$j])
                    {
                        $diffValues[] = utf8_encode($to[$j]);
                        $diffMask[] = 1;
                        $j--;  
                        continue;              
                    }
                }
                if ($i > -1)
                {
                    if ($dm[$i - 1][$j] == $dm[$i][$j])
                    {
                        $diffValues[] = utf8_encode($from[$i]);
                        $diffMask[] = -1;
                        $i--;
                        continue;              
                    }
                }
                {
                    $diffValues[] = utf8_encode($from[$i]);
                    $diffMask[] = 0;
                    $i--;
                    $j--;
                }
            }    
        
            $diffValues = array_reverse($diffValues);
            $diffMask = array_reverse($diffMask);
        
            return array('values' => $diffValues, 'mask' => $diffMask);
        }

        // There is Ś missing. Find and fix it:
        public function fixLatinS($arr){
            $tex = $arr["values"];
            $arr = $arr["mask"];
            // We are looking for pattern "...010...":
            $toBeReturned = -1;
            for($a=0; $a<count($arr)-2; $a++){
                if($arr[$a] == "0" && $arr[$a+1] == "1" && $arr[$a+2] == "0"){
                    // There is Ś missing at this place.
                    $toBeReturned = $a+1;
                }
            }
            return $toBeReturned;
        }
    }

    class CSVManager extends CSVTools{
        private string $CSVFilename;
        private string $CSVDelimiter;
        private $CSVFile;
        private array $CSVContent;
        private array $CSVSelectedContent;

        public function fixPolishCharacters($cleanString = false){ // Fix latin characters like Ż Ź Ą Ł Ś Ó Ń Ę Ć
            for($r=0; $r<count($this->CSVContent); $r++){
                for($c=0; $c<count($this->CSVContent[$r]); $c++){
                    $originalContent = $tmpContent = $this->CSVContent[$r][$c];
                    $originalField = $tmpContent;

                    // Replace unicode with utf-8 characters
                    $tmpContent = utf8_encode($tmpContent);
                    $tmpContent = str_replace("¯", "Ż", $tmpContent);
                    $tmpContent = str_replace("Ñ", "Ń", $tmpContent);
                    $tmpContent = str_replace("£", "Ł", $tmpContent);
                    $tmpContent = str_replace("Ó", "Ó", $tmpContent);
                    $tmpContent = str_replace("¥", "Ą", $tmpContent);
                    $tmpContent = str_replace("Ê", "Ę", $tmpContent);
                    $tmpContent = str_replace("æ", "Ć", $tmpContent);

                    // Add latin Ś letter in the right place:
                    $difference = $this->computeStringDiff($originalField, $tmpContent);
                    // Computed mask "010" means there is Ś missing. Append it:
                    $s_position = $this->fixLatinS($difference);
                    if($s_position != -1){
                        $tmpContent = substr_replace($tmpContent, "Ś", $s_position, 0);
                    }

                    // Clean string from any unicode leftovers
                    if($cleanString){
                        $tmpContent = str_replace(' ', '-SPACE-', $tmpContent); // Replaces all spaces with -SPACE-.
                        $tmpContent = preg_replace('/[^A-Za-z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ\.\,\-\_]/', '', $tmpContent); // Removes special chars.
                        $tmpContent = str_replace('-SPACE-', ' ', $tmpContent);
                    }

                    // Replace old string with new
                    $this->CSVSelectedContent[$r][$c] = $this->CSVContent[$r][$c] = $tmpContent;
                }
            }
        }

        public function makeCSVContent(){ // Load CSV content from CSV file
            if($this->CSVFilename != ""){
                if($this->CSVDelimiter != ""){
                    $success = true;
                    try{
                        $this->CSVFile = fopen($this->CSVFilename, "r");
                    }catch(Exception $e){
                        $success = false;;
                        echo("Error while making CSV content: " . $e->getMessage());
                    }
                    if($success){
                        $this->CSVContent = array();
                        while(($CSVLine = fgetcsv($this->CSVFile, 9999999, $this->CSVDelimiter)) !== false){
                            array_push($this->CSVContent, $CSVLine);
                        }
                        $this->CSVSelectedContent = $this->CSVContent;
                    }
                }else{
                    echo("Error while making CSV content: Delimiter not specified (for example use \$CSVManager->setDelimiter(\";\")");
                }
            }else{
                echo("Error while making CSV content: Filename not specified (use \$CSVManager->setFilename(\"NAME.csv\")");
            }
        }

        public function setFilename($filename){ // Specify filename
            $this->CSVFilename = $filename;
        }

        public function setDelimiter($delimiter){ // Specify delimiter
            $this->CSVDelimiter = $delimiter;
        }

        function __construct($filename = "", $delimiter = ""){
            if($filename != ""){
                // Filename specified, load it fo manager
                $this->CSVFilename = $filename;
            }
            if($delimiter != ""){
                $this->CSVDelimiter = $delimiter;
            }
            if($filename != "" && $delimiter != ""){
                $this->makeCSVContent();
            }
        }

        public function renderContent($renderType = "pre"){ // Render content "pre" or in "table"
            if($renderType == "pre"){
                echo("<pre>");
                    print_r($this->CSVSelectedContent);
                echo("</pre>");
            }
            if($renderType = "table"){
                echo("<table>");
                for($r=0; $r<count($this->CSVSelectedContent); $r++){
                    echo("<tr>");
                    for($c=0; $c<count($this->CSVSelectedContent[$r]); $c++){
                        echo("<td>");
                            echo($this->CSVSelectedContent[$r][$c]);
                        echo("</td>");
                    }
                    echo("</tr>");
                }
                echo("</table>");
            }
        }
        

    }

?>