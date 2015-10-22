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

include_once "abstractexportproduct.php";

class TwengaProduct extends AbstractExportProduct
{
    
    const TWENGA_CONDITION_NEW = "0"; 
    const TWENGA_CONDITION_USED = "1"; 
    const TWENGA_STOCK_AVAILABLE = "Y";
    const TWENGA_STOCK_NOTAVAILABLE = "N";
    const TWENGA_STOCK_BACKORDER = "R";
    
    protected $condition = self::TWENGA_CONDITION_NEW;
    protected $instock = self::TWENGA_STOCK_AVAILABLE; 
    
    /* Convert the Twenga Product to a Twenga XML definition */
    public function exportToString()
    {
        $output = ""; 
        $output .= "<product>" . PHP_EOL; 
        $output .= "<merchant_ref>{$this->sku}</merchant_ref>" . PHP_EOL; 
        $output .= "<product_url>{$this->url}</product_url>" . PHP_EOL; 
        $output .= "<image_url>{$this->imageUrl}</image_url>" . PHP_EOL; 
        $output .= "<price>{$this->price}</price>" . PHP_EOL; 
        $output .= "<shipping_cost>{$this->shippingCost}</shipping_cost>" . PHP_EOL; 
        $output .= "<designation>{$this->title}</designation>" . PHP_EOL; 
        $output .= "<description><![CDATA[{$this->description}]]></description>" . PHP_EOL; 
        $output .= "<in_stock>{$this->instock}</in_stock>" . PHP_EOL; 
        $output .= "<condition>{$this->condition}</condition>" . PHP_EOL; 
        $output .= "</product>" . PHP_EOL; 

        return $output; 
    }
    
}