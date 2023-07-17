<?php

 //simple page refirect
 function redirect($page){
    header('location: '. URLROOT.'/'.$page);
 }