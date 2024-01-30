<?php

namespace Grafst\ArtisanBug\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Grafst\ArtisanBug\ArtisanBug
 */
class ArtisanBug extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Grafst\ArtisanBug\ArtisanBug::class;
    }
}
