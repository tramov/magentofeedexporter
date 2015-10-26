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
    protected $rootCategory = false;

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

    protected function getProduct($productId) {
        $product = Mage::getModel('catalog/product');
        return $product->load($productId);
    }

    /** Load the root category of the website
     * 
     * @return Mage::category
     */
    
    protected function getRootCategory() {
        if (!$this->rootCategory) {
            $this->rootCategory = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId());
        }
        return $this->rootCategory;
    }

    /** Load all the products categories that belong to the active store 
     * 
     * @param Mage::Product $product
     * @return array
     */
    
    protected function getSameStoreCategories($product) {
        $rootCategory = $this->getRootCategory();
        return Mage::getResourceModel('catalog/category_collection')
                        ->addIdFilter($product->getCategoryIds())
                        ->addFieldToFilter('path', array('like' => $rootCategory->getPath() . '/%'))
                        ->getItems();
    }

    protected function getProductBreadCrumb($product) {

        $productCategories = array();
        $samestoreCategories = $this->getSameStoreCategories($product);

        /* This product is active, but has no category */
        if (count($samestoreCategories) == 0) {
            echo "Warning - Product " . $product->getSku() . " is in no category" . PHP_EOL; 
            return array();
        }

        /* Take the first category found, product can be in many */
        $categoryId = reset($samestoreCategories)->getId();

        /* Load all the categories, except for the root category */
        $category = Mage::getModel('catalog/category')->load($categoryId);
        $rootCategoryId = $this->getRootCategory()->getId();

        while ($category->getId() != $rootCategoryId) {
            $productCategories[] = $category->getName();
            $category = $category->getParentCategory();
        }

        /* Reverse the result, as we did a back to front walk */
        $result = array_reverse($productCategories);

        return $result;
    }

}
