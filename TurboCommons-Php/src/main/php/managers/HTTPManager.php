<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use UnexpectedValueException;
use org\turbocommons\src\main\php\model\BaseStrictClass;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * Class that contains functionalities related to the HTTP protocol and its most common operations
 */
class HTTPManager extends BaseStrictClass {


    /**
     * Test if the specified url exists by trying to connect to it.
     * Note that this method freezes the execution until the response is received from the given url so use it carefully.
     * Response will be longer for non existing urls cause it will wait till the request timeout completes.
     *
     * @param string $url An internet address to check
     *
     * @return boolean True if url exists and is accessible, false if the url could not be accessed.
     */
    public function urlExists($url){

        if(StringUtils::isEmpty($url)){

            return false;
        }

        $validationManager = new ValidationManager();

        // Avoid performing an http request if the url is invalid
        if(!$validationManager->isUrl($url)){

            return false;
        }

        $headers = $this->getUrlHeaders($url);

        if($headers === null){

            return false;
        }

        foreach([404, 405] as $code){

            if (NumericUtils::isNumeric($code) && strpos($headers[0], strval($code)) !== false){

                return false;
            }
        }

        return true;
    }


    /**
     * Get the Http headers for a given url.
     *
     * @param string $url The url that for which we want to get the http headers.
     *
     * @return array Url headers split by each line as an array element or null if no headers could be found
     */
    public function getUrlHeaders($url){

        // Check that curl is available
        if(!function_exists('curl_init')){

            throw new UnexpectedValueException('HTTPUtils::getUrlHeaders: Curl must be enabled');
        }

        $handle = curl_init($url);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_USERAGENT, true);

        $headers = curl_exec($handle);

        curl_close($handle);

        if(empty($headers)){

            return null;
        }

        return explode(PHP_EOL, $headers);
    }


    /**
     * Synchronously checks if the specified domain is free for Internet registering by calling several whois services.
     *
     * @param string $domain The domain to check
     *
     * @return boolean True if the specified domain is free can be registered, false if the domain is already registered by somebody
     */
    public static function isDomainFreeToRegister($domain){

        $domain = strtolower($domain);

        //Remove www. sequence from the string if exists
        if (substr($domain, 0, 4) == 'www.'){

            $domain = substr($domain, 4, strlen($domain));
        }

        //Retrieve current domain extension
        $ext = explode('.', $domain);
        $ext = $ext[count($ext) - 1];

        //Choose whois server & pattern depending the domain extension
        switch($ext){

            case 'biz':
                $server = 'whois.neulevel.biz';
                $pattern = 'Not found: ';
                break;

            case 'info':
                $server = 'whois.afilias.info';
                $pattern = 'NOT FOUND';
                break;

            case 'cat':
                $server = 'whois.cat';
                $pattern = 'NOT FOUND';
                break;

            default:
                $server = 'whois.crsnic.net';
                $pattern = 'No match for ';
                break;
        }

        // we normally use the "$errno: $errstr" to show if something's happened instead of "1". But in this case, we must return a 1 value if
        // no communication is obtained, so our users can continue using the app
        if(!($fp = fsockopen ($server, 43, $errnr, $errstr, 20))){

            die(1);
        }

        fputs($fp, $domain."\n");

        while (!feof($fp)){

            $serverReturn = fgets($fp, 2048);

            if(substr_count($serverReturn, $pattern) > 0){

                fclose($fp);
                return true;
            }
        }

        fclose($fp);

        return false;
    }
}

?>