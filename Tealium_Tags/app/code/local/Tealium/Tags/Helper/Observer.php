<?php

/*
 * This helper class is used to create pages that contain only the udo and
 * other relevant Tealium code; no html. Useful for quick verification.
 */
class Tealium_Tags_Helper_Observer extends Mage_Core_Helper_Abstract
{

    public function __construct()
    {
        // no need for extended constructor
    }

    public function apiHandler($observer)
    {
        // if the "tealium_api" parameter is set to true, set the response
        // to only contain relevant Tealium logic.
        if (
            isset($_REQUEST["tealium_api"])
                && $_REQUEST["tealium_api"] == "true"
        ) {
            $response = $observer->getEvent()->getFront()->getResponse();
            $html = $response->getBody();
            preg_match('/\/\/TEALIUM_START(.*)\/\/TEALIUM_END/is', $html, $matches);
            $javaScript = "// Tealium Magento Callback API";
            $javaScript .= $matches[1];
            $response->setBody($javaScript);
        }

        return $this;
    }

}
