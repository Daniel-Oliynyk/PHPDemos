<style>
    * {
        font-size: large;
    }
    .square {
        width: 20px;
        border: 1px solid black;
    }
</style>
<?php
if (empty($_GET)) {
    echo "<form>";
    for ($y = 0; $y < 9; $y++) {
        for ($x = 0; $x < 9; $x++) echo "<input class='square' name='$x,$y' maxlength=1>";
        echo "<br>";
    }
    echo "<br><input type='submit' value='Solve'>";
    echo "</form>";
    echo "<br><br>";
}
else {
    $solved = $_GET;
    $it = 0;
    while (!complete($solved) && $it < 100) {
        $it++;
        
        for ($y = 0; $y < 3; $y++) {
            for ($x = 0; $x < 3; $x++) {
                $rd = remainingData(getSquare($solved, $x, $y));
                foreach ($rd[0] as $num) {
                    $ps = possibleSquares($solved, $rd[1], $num);
                    if (count($ps) === 1) {
                        $solved[$ps[0]] = $num;
                        $rd[1] = remainingData(getSquare($solved, $x, $y))[1];
                        //echo "a. $num -> {$ps[0]}<br>";
                    }
                }
            }
        }
        
        for ($y = 0; $y < 9; $y++) {
            $rd = remainingData(getRow($solved, $y));
            foreach ($rd[0] as $num) {
                $ps = possibleSquares($solved, $rd[1], $num);
                if (count($ps) === 1) {
                    $solved[$ps[0]] = $num;
                    $rd[1] = remainingData(getRow($solved, $y))[1];
                    //echo "b. $num -> {$ps[0]}<br>";
                }
            }
        }
        
        for ($x = 0; $x < 9; $x++) {
            $rd = remainingData(getColumn($solved, $x));
            foreach ($rd[0] as $num) {
                $ps = possibleSquares($solved, $rd[1], $num);
                if (count($ps) === 1) {
                    $solved[$ps[0]] = $num;
                    $rd[1] = remainingData(getColumn($solved, $x))[1];
                    //echo "c. $num -> {$ps[0]}<br>";
                }
            }
        }
    }
    
    for ($y = 0; $y < 9; $y++) {
        for ($x = 0; $x < 9; $x++) {
            echo "<input class='square' value='{$solved["$x,$y"]}' disabled>";
        }
        echo "<br>";
    }
    echo "<br>";
    echo "<form><input type='submit' value='Back'></form>";
    
    //print_r(possibleSquares($solved, remainingData(getSquare($solved, 0, 0))[1], 7));
    //print_r(remainingData(getSquare($solved, 1, 2))[1]);
}

function getSquare($solved, $sqX, $sqY) {
    $results = array();
    for ($x = $sqX * 3; $x < ($sqX * 3) + 3; $x++) {
        for ($y = $sqY * 3; $y < ($sqY * 3) + 3; $y++) $results["$x,$y"] = $solved["$x,$y"];
    }
    return $results;
}

function getRow($solved, $y) {
    $results = array();
    for ($i = 0; $i < 9; $i++) $results["$i,$y"] = $solved["$i,$y"];
    return $results;
}

function getColumn($solved, $x) {
    $results = array();
    for ($i = 0; $i < 9; $i++) $results["$x,$i"] = $solved["$x,$i"];
    return $results;
}

function remainingData($squares) {
    $nums = array_diff(array(1, 2, 3, 4, 5, 6, 7, 8, 9), $squares);
    $remaining = array();
    foreach ($squares as $key => $value) {
        if (!is_numeric($value)) $remaining[] = $key;
    }
    return array($nums, $remaining);
}

function possibleSquares($solved, $squares, $num) {
    $result = array();
    foreach ($squares as $sq) {
        $x = explode(",", $sq)[0];
        $y = explode(",", $sq)[1];
        if (!in_array($num, getRow($solved, $y)) && !in_array($num, getColumn($solved, $x))) $result[] = $sq;
    }
    return $result;
}

function complete($solved) {
    foreach ($solved as $val) {
        if (!is_numeric($val)) return false;
    }
    return true;
}
?>
