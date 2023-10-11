<?php

namespace Datainterface;

use ConfigurationSetting\ConfigureSetting;
use GlobalsFunctions\Globals;

class SecurityChecker
{
    public static function isConfigExist(): bool
    {
        if(!empty(ConfigureSetting::getDatabaseConfig()) && self::checkPrivileges($queryLine)){
            return true;
        }
        return false;
    }

    public static function checkPrivileges($queryLine): null|bool
    {
        if(is_null($queryLine))
        {
            return true;
        }

        $configs = ConfigureSetting::getDatabaseConfig();
        if(!empty($configs['options_db']))
        {
            $currentDomain = Globals::serverHost();
            $settingForDomain = [];
            foreach ($configs['options_db']['privileges'] as $key=>$value)
            {
                if($value['domain'] === $currentDomain)
                {
                    $settingForDomain = $value['allowed'];
                    break;
                }
            }

            if(!empty($settingForDomain))
            {
                foreach ($settingForDomain as $key=>$value)
                {
                    if(str_starts_with(strtolower($queryLine), strtolower($value)))
                    {
                        return true;
                    }
                }
            }
        }

        //response by die
        echo <<<EOD
        <div style="margin: 10em;"><h3>Access denied due to resource issues.</h3></div>
EOD;
        exit;
    }
}