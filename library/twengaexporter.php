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


include_once "abstractexporter.php";
include_once "twengaproduct.php";

class TwengaExport extends AbstractExporter
{
    protected $feedfile; 
    protected $minPrice; 
    protected $shippingPrice; 
    
    private $handle; 
    
    public function setFeedfile($feedfile) {
        $this->feedfile = $feedfile;
        return $this;
    }

    public function setMinPrice($minPrice) {
        $this->minPrice = $minPrice;
        return $this;
    }

    public function setShippingPrice($shippingPrice) {
        $this->shippingPrice = $shippingPrice;
        return $this;
    }
    
    private function createFeedFile()
    {
        $this->handle = fopen($this->feedfile, "w");
        fwrite($this->handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
        fwrite($this->handle, '<products>' . PHP_EOL);
    }
    
    private function closeFeedFile()
    {
        fwrite($this->handle, '</products>' . PHP_EOL);
        fclose($this->handle); 
    }
    
    private function writeProductToFeed($product)
    {
        fwrite($this->handle, $product);
    }

    public function run()
    {
        $this->createFeedFile(); 
        
        foreach($this->getProductIds() as $productId)
        {
            $magentoProduct = $this->getProduct($productId);
            $exportProduct = new TwengaProduct();
           
            $exportProduct->setTitle($magentoProduct->getName());
            $exportProduct->setDescription($magentoProduct->getDescription()); 
            $exportProduct->setUrl($magentoProduct->getProductUrl());
            
            $imageUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $magentoProduct->getImage();
            $exportProduct->setImageUrl($imageUrl);

            $price = round($magentoProduct->getFinalPrice(), 2);            
            $exportProduct->setPrice($price);
            $exportProduct->setSku($magentoProduct->getSku());
            $exportProduct->setShippingCost($this->shippingPrice);
            
            $this->writeProductToFeed($exportProduct->exportToString());
        }
        
        $this->closeFeedFile(); 
    }
    
}