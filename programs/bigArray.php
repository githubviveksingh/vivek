
<?php
$numbers = array(12,23,45,20,5,6,34,17,9,56,999);
$length      = count($numbers);
$max         = $numbers[0];
for($i=1;$i<$length;$i++)
  {
      if($numbers[$i]>$max)
        {
          $max=$numbers[$i];
        }
  }
echo "The biggest number is ".$max;
?>
