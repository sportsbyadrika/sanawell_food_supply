<?php

function generateTempPassword($length = 8)
{
    return substr(str_shuffle(
        'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'
    ), 0, $length);
}