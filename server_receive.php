<?php
$data = file_get_contents("php://input");
echo $data;
file_put_contents(__DIR__ .'/resceive.log', $data);