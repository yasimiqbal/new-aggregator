<?php

function setDefaultListSize($listSize)
{
    return ($listSize >= 50) ? 50 : (empty($listSize) ? 25 : $listSize);
}
