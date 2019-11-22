<?php

namespace Uithread\MgovSMS\Facades;

use Illuminate\Support\Facades\Facade;

class MgovSMSFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'mgov-sms';
    }
}
