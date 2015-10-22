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

abstract class AbstractExportProduct 
{
    protected $sku; 
    protected $url; 
    protected $imageUrl; 
    protected $price; 
    protected $shippingCost; 
    protected $title; 
    protected $description; 
    protected $inStock; 
    protected $itemDisplay; 
    protected $condition; 
    
    private   $translationTable; 
    
    public function __construct()
    {
        $this->initTranslationTable(); 
    }
    
    /** Initialise the translation table used to filter Unicode characters
     *  to their HTML equivalents
     */
    
    protected function initTranslationTable()
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        
        $trans[chr(38)] = '&amp;';    // &
        $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
        $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
        $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
        $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
        $trans[chr(134)] = '&dagger;';    // Dagger
        $trans[chr(135)] = '&Dagger;';    // Double Dagger
        $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
        $trans[chr(137)] = '&permil;';    // Per Mille Sign
        $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
        $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
        $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
        $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
        $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
        $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
        $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
        $trans[chr(149)] = '&bull;';    // Bullet
        $trans[chr(150)] = '&ndash;';    // En Dash
        $trans[chr(151)] = '&mdash;';    // Em Dash
        $trans[chr(152)] = '&tilde;';    // Small Tilde
        $trans[chr(153)] = '&trade;';    // Trade Mark Sign
        $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
        $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
        $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
        $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
        $trans['euro'] = '&euro;';    // euro currency symbol
        ksort($trans);

        $this->translationTable = $trans; 
    }
    
   /** Remove any invalid characters & HTML from the string and return pure
     *  low-bit ascii. Attempts to replace common unicode characters with
     *  their HTML equivalents
     *  
     * @param string $input
     * @return string filtered input
     */

    protected function cleanString($input) {

        /* Replace html codes with unicode */
        foreach ($this->translationTable as $k => $v) {
            $input = str_replace($v, $k, $input);
        }
        
        /* Remove symbols remove tags, remove low non-ascii */        
        $htmlfree = html_entity_decode(strip_tags($input));
        /* Replace any left over & */
        $htmlfree = str_replace("&","&#038;",$htmlfree);
        
        return preg_replace('/[^(\x20-\x7F)]*/', '', $htmlfree);
    }

    public function getSku() {
        return $this->sku;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getShippingCost() {
        return $this->shippingCost;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getInStock() {
        return $this->inStock;
    }

    public function getItemDisplay() {
        return $this->itemDisplay;
    }

    public function getCondition() {
        return $this->condition;
    }

    public function setSku($sku) {
        $this->sku = $sku;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function setShippingCost($shippingCost) {
        $this->shippingCost = $shippingCost;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $this->cleanString($title);
        return $this;
    }

    public function setDescription($description) {
        $this->description = $this->cleanString($description);
        return $this;
    }

    public function setInStock($inStock) {
        $this->inStock = $inStock;
        return $this;
    }

    public function setItemDisplay($itemDisplay) {
        $this->itemDisplay = $itemDisplay;
        return $this;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
        return $this;
    }
    
    abstract public function exportToString();
    
}
