<?php
use Cnsi\Lock\Lock;
require 'vendor/autoload.php';

$lock = new \Cnsi\Lock\Lock('test',60,120,'kental0910@gmail.com');
$lock->lock();
sleep(30);
$lock->remove();
