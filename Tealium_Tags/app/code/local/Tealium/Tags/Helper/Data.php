<?php

require_once (Mage::getBaseDir('lib') . '/Tealium/Tealium.php');

/*
 * Helper class for the Tealium Tags module.
 * Implements basic methods for doing things such as getting module config,
 * including info like account name, profile, environment, etc. Also includes
 * a few other module specific utility methods.
 */
class Tealium_Tags_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $tealium;
    protected $store;
    protected $page;

    /*
     * Setup the helper object so that it's ready to do work
     */
    
    /***** #mynotes *****
     * What is the need to pass by reference?
     */
    public function init(&$store, &$page = array(), $pageType)
    {
        // initialize basic profile settings
        $account = $this->getAccount($store);
        $profile = $this->getProfile($store);
        $env = $this->getEnv($store);

        $data = array(
            "store" => $store,
            "page" => $page
        );
        
        $this->store = $store;
        $this->page = $page;
        $this->tealium =
            new Tealium($account, $profile, $env, $pageType, $data);

        return $this;
    }

    
    public function addCustomDataFromSetup(&$store, $pageType)
    {
        /***** #mynotes *****
         * the "$data" variable is referenced in the custom UDO
         * definition file
         */
        $data = array(
            "store" => $this->store,
            "page" => $this->page
        );
        
        if (Mage::getStoreConfig('tealium_tags/general/custom_udo_enable', $store)) {
            // To define a custom udo, define the "$udoElements" variable, which
            // is an associative array with page types as keys and functions
            // that return a udo for the page types as the value.
            
            // One way to define a custom udo is to include an external file
            // that defines "$udoElements"
            include_once (Mage::getStoreConfig(
                'tealium_tags/general/udo', $store));

            // Another way to define a custom udo is to define a "getCustomUdo"
            // method, which is used to set "$udoElements"
            if (method_exists($this, "getCustomUdo")) {
                $customUdoElements = getCustomUdo();
                if (
                    is_array($customUdoElements) &&
                    self::isAssocArray($customUdoElements)
                ) {
                    $udoElements = $customUdoElements;
                }
            } elseif (
                !isset($udoElements)
                || (
                    isset($udoElements)
                    && !self::isAssocArray($udoElements)
                )
            ) {
                $udoElements = array();
            }

            // if a custom udo is defined for the page type, set the udo
            if (isset($udoElements[$pageType])) {
                $this->tealium->setCustomUdo($udoElements[$pageType]);
            }
        }

        return $this;
    }

    /*
     * Set custom data by updating the udo of the Tealium object belonging to
     * "this" helper
     */
    public function addCustomDataFromObject($udoObject)
    {
        if (is_array($udoObject) && self::isAssocArray($udoObject)) {
            $this->tealium->updateUdo($udoObject);
        }
        
        return $this;
    }

    /*
     * Determine if an array is an associative array
     */
    protected static function isAssocArray($array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /*
     * Check if the udo is enabled. Used to determine if udo javascript should
     * be rendered.
     */
    public function isEnabled($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/enable', $store);
    }

    /*
     * One Page Checkout should be explicitly enabled to render a udo on
     * the page
     */
    public function enableOnePageCheckout($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/onepage', $store);
    }

    /*
     * Returns true if an external udo is enabled. Used to override the default
     * udo and allow for a customized udo.
     */
    public function externalUdoEnabled($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/custom_udo_enable', $store);
    }

    /*
     * Return the url used to download the tag config. Rendered as part of the
     * universal code snippet.
     */
    public function getTealiumBaseUrl($store)
    {
        $account = $this->getAccount($store);
        $profile = $this->getProfile($store);
        $env = $this->getEnv($store);
        return "//tags.tiqcdn.com/utag/$account/$profile/$env/utag.js";
    }

    /*
     * While "this" helper provides a single interface to utility functions,
     * the "tealium" object manages udo operations. This function returns
     * the tealium object for times when it's useful to work with the tealium
     * object directly.
     */
    public function getTealiumObject()
    {
        return $this->tealium;
    }

    /*
     * Return the account name, typically the client company name.
     */
    public function getAccount($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/account', $store);
    }

    /*
     * Return the profile name. Typically "main", or often the site name
     * if there are multiple profiles.
     */
    public function getProfile($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/profile', $store);
    }

    /*
     * Return the environment. Typically "dev", "qa", or "prod".
     */
    public function getEnv($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/env', $store);
    }

    /*
     * When overriding the default udo with a custom one, the code that
     * overrides the default must live somewhere. This function returns
     * the path to the file on the server in which a custom udo is defined.
     */
    public function getUDOPath($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/udo', $store);
    }

    /*
     * When developing, it's sometimes useful to only view the rendered
     * universal code snippet and udo instead of the entire page. This can
     * be done by appending the query param "?tealium_api=true" to the end
     * of the url in the browser. However this feature is only enabled if
     * the "api_enable" config is set to true.
     * 
     * This function returns true when the api is enabled.
     */
    public function getAPIEnabled($store)
    {
        return Mage::getStoreConfig('tealium_tags/general/api_enable', $store);
    }

    /*
     * Place Tealium code in external javaScript file 
     * NOTE Order confirmation page will always load script on page
     */
    public function getIsExternalScript($store)
    {
        return Mage::getStoreConfig(
            'tealium_tags/general/external_script',
            $store
        );
    }

    /*
     * When placing the Tealium code in an external javaScript file, it's
     * either loaded syncronously or asyncronously.
     * 
     * This function returns either "async" or "sync" depending on the config.
     */
    public function getExternalScriptType($store)
    {
        $async = Mage::getStoreConfig(
            'tealium_tags/general/external_script_type',
            $store
        );
        return $async ? "async" : "sync";
    }

    /*
     * Sometimes it's useful to send the entire udo to a server for diagnostics.
     * This function returns a tag in the form of an html <img> element that
     * will send the url encoded udo to a specified server if the feature is
     * enabled in the config.
     */
    public function getDiagnosticTag($store)
    {
        if (Mage::getStoreConfig(
                'tealium_tags/general/diagnostic_enable',
                $store
            )
        ) {
            $utag_data = urlencode($this->tealium->render("json"));
            $url = Mage::getStoreConfig(
                'tealium_tags/general/diagnostic_tag',
                $store
            )
                . '?origin=server&user_agent='
                . $_SERVER ['HTTP_USER_AGENT']
                . '&data='
                . $utag_data;
            return '<img src="' . $url . '" style="display:none"/>';
        } else {
            return "";
        }
    }

}
