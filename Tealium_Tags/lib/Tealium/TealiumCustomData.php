<?php

class TealiumExtendData
{

    private static $store;
    private static $page;

    public static function setStore($store)
    {
        TealiumExtendData::$store = $store;
    }

    public static function setPage($page)
    {
        TealiumExtendData::$page = $page;
    }

    public function getHome()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";

        return $outputArray;
    }

    public function getSearch()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";

        return $outputArray;
    }

    public function getCategory()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";

        return $outputArray;
    }

    public function getProductPage()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";
        // make sure any product values are in an array

        return $outputArray;
    }

    public function getCartPage()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";
        // make sure any product values are in an array

        return $outputArray;
    }

    public function getOrderConfirmation()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";
        // make sure any product values are in an array

        return $outputArray;
    }

    public function getCustomerData()
    {
        $store = TealiumExtendData::$store;
        $page = TealiumExtendData::$page;

        $outputArray = array();
        //$outputArray['custom_key'] = "value";

        return $outputArray;
    }

}

TealiumExtendData::setStore($data["store"]);
TealiumExtendData::setPage($data["page"]);

$udoElements = array(
    'Home' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getHome();
    },
    'Search' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getSearch();
    },
    'Category' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getCategory();
    },
    'ProductPage' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getProductPage();
    },
    'Cart' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getCartPage();
    },
    'Confirmation' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getOrderConfirmation();
    },
    'Customer' => function() {
        $tealiumData = new TealiumExtendData();
        return $tealiumData->getCustomerData();
    }
);

