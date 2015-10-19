<?php

/*
 * Copyright 2015 Martijn Dijksterhuis <martijn@dijksterhuis.org>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/** Twenga Magento Feed Exporter
 * 
 *  Usage: 
 * 
 *  php twenga.php --magedir=.. --shipping=6.60 --feedfile=output.xml --store=default
 * 
 *  --magedir   Req. the path where Magento is installed
 *  --shipping  Req. constant used to indicate shipping fee, for more complex
 *                   logic, you need to build your own
 *  --feedfile  Opt. Name of the exported XML file (output.xml)
 *  --store     Opt. Id of the Magento store to export (default)
 * 
 */

include "library/twengaexporter.php";

$longopts  = array(
    "magedir:",      // Required value
    "shipping:",     // Required value
    "feedfile::",    // Optional value, defaults to "output.xml"
    "store::",       // Optional value, defaults to "default" 
);

$options = getopt("", $longopts);

if (!isset($options['magedir']))
{
    die("--magedir= is a required option");
}
else
{
    $magePath = $options['magedir'] . "/App/Mage.php";
    if (!file_exists($magePath))
    {
        die("$magePath cannot be found");
    }
}

if (!isset($options['shipping']) )
{
    die("--shipping= is a required option");
}
else
{
    $shippingPrice = $options['shipping'];    
}

$feedFile = isset($options['feedfile']) ? $options['feedfile'] : "output.xml";
$storeName = isset($options['store']) ? $options['store']: "default";


/* Bootstrap Magento */
set_time_limit(0);
require_once $magePath;
Mage::app($storeName);

/* Init the twenga exporter */
$twenga = new TwengaExport(); 
$twenga->setFeedfile($feedFile);
$twenga->setShippingPrice($shippingPrice);

$twenga->run(); 

