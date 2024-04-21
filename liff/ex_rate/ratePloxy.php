<?php

$rate = file_get_contents("https://api.excelapi.org/currency/rate?pair=usd-jpy");

echo $rate;

