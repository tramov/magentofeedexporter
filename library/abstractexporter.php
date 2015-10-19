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


abstract class AbstractExporter {

    protected $visibilty = 4;
    protected $translationTable = array(); 
    
    /** Standard visibility is 4, which in magento equates to catalog, search
     * 
     * @param type $visibilty
     * @return \BaseExport
     */
    
    public function setVisibilty($visibilty) {
        $this->visibilty = $visibilty;
        return $this;
    }
    
    /** Load all the magento products 
     * 
     * @return array with all the product ID's
     */
    protected function getProductIds() {
        $products = Mage::getModel('catalog/product')->getCollection();
        $products->addAttributeToFilter('status', 1); //enabled
        $products->addAttributeToFilter('visibility', $this->visibilty); //catalog, search
        $products->addAttributeToSelect('*');
        return $products->getAllIds();
    }
    
    protected function getProduct($productId)
    {
        $product = Mage::getModel('catalog/product');
        return $product->load($productId);
    }

}
