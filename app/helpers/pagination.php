<?php
function start_sn($result)
{
    return ($result->currentPage() * $result->perPage()) - $result->perPage() + 1;
}

function end_sn($result)
{
    return $result->total() - (($result->currentPage() - 1) * $result->perPage());
}
