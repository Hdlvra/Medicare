<?php
$jour = $_GET['jour'];
$jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
$jour_num = array_search($jour, $jours);

$dates = [];
$today = new DateTime();
$today_num = (int)$today->format('N') - 1;

$days_ahead = ($jour_num - $today_num + 7) % 7;

if ($days_ahead === 0) $days_ahead = 7;
$today->modify("+$days_ahead days");

for ($i = 0; $i < 5; $i++) {
    $dates[] = $today->format('Y-m-d');
    $today->modify('+7 days');
}

echo json_encode($dates);
?>