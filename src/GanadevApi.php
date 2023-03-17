<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DeyanArdi\GanadevApi\
 */
class GanadevApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ganadevnotif';
    }
}
