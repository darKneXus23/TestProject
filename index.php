<?php
require_once 'components/nav.php';
require_once 'components/footer.php';
require_once 'components/wordlist.php';

//-----------------------------------------------------------------------------------------------------------

$body = 'please enter any combination of characters (under 8 characters)';
$letterArray = array();
$permuteArray = array();
$resultArray = array();
$input = "";

// ----------------------------------------------------------------------------------------------------------

if (isset($_POST['permute'])) {
    $letterArray = str_split(strtolower(str_replace(' ', '', $_POST['permute'])));
    $permuteArray = Cleaner(AllPermutations($letterArray));
    $resultArray = CompareWords($wordlist, $permuteArray, count($letterArray));
    $body = "";
    $input = str_replace(' ', '', $_POST['permute']);
    foreach ($resultArray as $value) {
        if (strrev($value) == $value) {
            $body .= "<div class='col m-2 d-flex flex-column align-items-center border'><a href='https://www.dictionary.com/browse/{$value}'>{$value}</a> (palindrome)</div>";
        } else {
            $body .= "<div class='col m-2 d-flex flex-column align-items-center border'><a href='https://www.dictionary.com/browse/{$value}'>{$value}</a></div>";
        }
    }
}

//----------------------------------FUNCTIONS----------------------------------------------------------------

// compares string to wordlist
function CompareWords($wordlist, $permuteArray, $letterCount) {
    $returnArray = array();
    for ($count=$letterCount;$count>2;$count--){
        for ($i=0;$i<count($permuteArray);$i++) {
            $compareWord = substr($permuteArray[$i],0,$count);
            $tempWordArray = explode(PHP_EOL, $wordlist[mb_substr($permuteArray[$i], 0, 1)]);
            for ($j=0;$j<count($tempWordArray);$j++) {
                if ($tempWordArray[$j] == $compareWord) {
                    if (in_array($tempWordArray[$j], $returnArray)) {
                        continue;
                    } else {
                        $returnArray[] = $tempWordArray[$j];
                    }
                } else {
                    continue;
                }
            }
        }
    }
    return $returnArray;
}

// makes string out of permutation array for comparison
function Cleaner($permuteArray) {
    $returnArray = array();
    for ($i=0;$i<count($permuteArray);$i++) {
        $returnArray[] = implode($permuteArray[$i]);
        };
    return $returnArray;
}

// finds all permutations of given array (string from input has to get converted into array before)
function AllPermutations($inArray, $inProcessedArray = array())
{
    $returnArray = array();
    foreach($inArray as $key=>$value)
    {
        $copyArray = $inProcessedArray;
        $copyArray[$key] = $value;
        $tempArray = array_diff_key($inArray, $copyArray);
        if (count($tempArray) == 0)
        {
            $returnArray[] = $copyArray;
        }
        else
        {
            $returnArray = array_merge($returnArray, AllPermutations($tempArray, $copyArray));
        }
    }
    return $returnArray;
}

?>
<!-- --------------------------------------PHP END---------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "components/boot.php" ?>
    <link rel="stylesheet" href="styles/styles.css">
    <title>StefanKanta</title>
</head>
<body>
    <?= $navbar ?>
    <div class="d-flex flex-column align-items-center">
        <h3 class="my-1">Wordfinder:</h1>
        <div class="my-3">(wordlist is not complete, ~70k words)</div>
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2">
                <div class='col d-flex flex-column align-items-center'>
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="mx-5 d-flex flex-column align-items-center" enctype="multipart/form-data">
                        <label for='permute' class='form-label mb-1'>Insert string</label>
                        <input class='form-control py-1' type='text' name='permute' id='permute' placeholder='Insert String'>
                        <input class="btn btn-sm btn-success mt-3 mx-5" type="submit" name="submit" value="GO">
                    </form>
                </div>
                <div class='col d-flex flex-column align-items-center'>
                    <div class="row row-cols-1 row-cols-md-5">
                        <h5>Input: <?= $input ?></h5>
                    </div>
                    <div class="row d-flex justify-content-between flex-wrap">
                        <?= $body ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $footer ?>
</body>
</html>