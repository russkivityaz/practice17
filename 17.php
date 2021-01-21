<?php
    include 'massiv.inc.php';

    /* принимает как аргумент одну строку — склеенное ФИО. Возвращает как результат 
    массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’. */

    /* Пример: как аргумент принимается строка «Иванов Иван Иванович», 
    а возвращается массив [‘surname’ => ‘Иванов’ ,‘name’ => ‘Иван’, ‘patronomyc’ => ‘Иванович’]. */
    function getPartsFromFullname($fullNames)
        {
            $namesKey = ['surname','name', 'patronomic'];
            
            return array_combine($namesKey, explode(' ', $fullNames));

        };
    
    
    /* принимает как аргумент три строки — фамилию, имя и отчество. 
    Возвращает как результат их же, но склеенные через пробел. */

    /* Пример: как аргументы принимаются три строки «Иванов», «Иван» и «Иванович», 
    а возвращается одна строка — «Иванов Иван Иванович». */
    function getFullnameFromParts($surname, $name, $patronomic)  
        {
            return $surname . " " . $name . " " . $patronomic;
        };


    function getShortName($fullNames)
        {
            $massFullName = getPartsFromFullname($fullNames);
            return $massFullName['name'] . " " . mb_substr($massFullName['surname'], 0, 1) . ".";
            
        };



    function getGenderFromName($fullNames)
        {
            $gender = 0;
            $massFullName = getPartsFromFullname($fullNames);
            
            
            if ((mb_substr($massFullName['patronomic'], -3, 3)) == "вна") {
                $gender -= 1;
            } elseif ((mb_substr($massFullName['patronomic'], -2, 2)) == "ич") {
                $gender += 1;
            }

            if ((mb_substr($massFullName['surname'], -2, 2)) == 'ва') {
                $gender -= 1;
            } elseif ((mb_substr($massFullName['surname'], -1, 1)) == "в") {
                $gender += 1;
            }

            if ((mb_substr($massFullName['name'], -1, 1)) == 'а') {
                $gender -= 1;
            } elseif ((mb_substr($massFullName['name'], -1, 1)) == "й" || (mb_substr($massFullName['name'], -1, 1)) == "н") {
                $gender += 1;
            }

           if ($gender<0){
            return 'женщина';
           } elseif  ($gender>0) {
            return 'мужчина';
           } else {
            return 'оно';    
           }

           
      
           
        };
        
        

    function getGenderDescription($personMassiv){     
        $men = 0;
        $women = 0;
        $ono = 0;

        for($i=0; $i<count($personMassiv); $i++)
            {
                $a = ($personMassiv[$i]['fullname']);
                $b = getGenderFromName($a);
                
                if ($b == 'мужчина') {
                    $men += 1;
                } elseif ($b == 'женщина') {
                    $women += 1;
                } elseif ($b == 'оно') {
                    $ono += 1; 
                }
            }
            echo "\n\n";
            echo "Гендерный состав аудитории: \n";
            echo "------------------- \n";
            echo "Мужчины -" . " " .  round($men/11*100, 2) ."%" . "\n";   
            echo "Женщины -" . " " . round($women/11*100, 2) ."%"  . "\n";
            echo "Не удалось определить -" . " " . round($ono/11*100, 2) ."%" . "\n";          
    }



    function getPerfectPartner($surname, $name, $patronomic, $personMassiv)
    {
        $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
        $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
        $patronomic = mb_convert_case($patronomic, MB_CASE_TITLE_SIMPLE);
        
        $personOne = getFullnameFromParts($surname, $name, $patronomic);
        $personGenderOne = getGenderFromName($personOne);
        
        $personTwo = '';
        $personGenderTwo = 'оно';
    
        function para($personGenderOne, $personGenderTwo, $personMassiv, $personTwo)
        {
            if ($personGenderOne != $personGenderTwo && $personGenderTwo != 'оно'){
                return $personTwo;
            } else {
                $randSelect = rand(1, count($personMassiv)-1);
                $personTwo = $personMassiv[$randSelect]['fullname'];
                $personGenderTwo = getGenderFromName($personTwo);
                return para($personGenderOne, $personGenderTwo, $personMassiv, $personTwo);
            }
        }    
        
        $personTwo = para($personGenderOne, $personGenderTwo, $personMassiv, $personTwo);
        $personTwo = getShortName($personTwo);
        $personOne =  getShortName($personOne);

 
        echo $personOne . " + " . $personTwo . " = " . "Идеально на " . round(rand(50, 100)/1.1,2) . "%";

        
     
        /*echo $personOne . "\n";
        echo $personGenderOne  . "\n"; 

        echo $personTwo . "\n";
        echo $personGenderTwo  . "\n"; */
    }


    /*print_r(getShortName('Бабкин Артем Валерьевич'));    
    print_r(getFullnameFromParts('Бабкин', 'Тимофей', 'Артемович'));  
    print_r(getPartsFromFullname('Бабкина Елизавета Сергеевна'));*/
    /*getGenderFromName('Зенина Елизавета Сергеевна');*/
    /*getGenderDescription($example_persons_array);*/

    getPerfectPartner('бАБкин', 'тимоФеЙ', 'Артемович', $example_persons_array);


?>
