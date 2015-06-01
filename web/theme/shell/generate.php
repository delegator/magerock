<?php

require_once __DIR__ . '/../../magento/shell/abstract.php';

class Delegator_Generate extends Mage_Shell_Abstract
{

    private $restore = "\033[0m";
    private $bold = "\033[1m";
    private $blue = "\033[00;34m";

    public function _notify($message, $newline = true)
    {
        echo $this->bold . $this->blue . " :: " . $this->restore . $this->bold . $message . $this->restore;
        if ($newline) {
            echo "\n";
        }
    }

    public function generate($max) 
    {
        if ($max < 1) {
            $max = 1;
        }
        $this->_notify('Make sure you have at least 2 categories');
        $this->_notify('Generating Products');

        for ($i = 1; $i < $max+1; $i++) {
            $description = file_get_contents('http://loripsum.net/api/1/verylong/plaintext');
            $short = file_get_contents('http://loripsum.net/api/1/short/plaintext');
            
            $pruned = preg_replace("/[^A-Za-z0-9 ]/", '',$short);
            $exploded = explode(' ', $pruned);
            $diffed = array_diff($exploded, [' ', '']);
            shuffle($diffed);
            $first = $diffed[0];
            if ($first == ' ' || $first == '') {
                $i--;
                continue;
            }
            $name = ucwords($first);

            $return = $this->makeProduct($name, $short, $description, "$i / $max (" . ($i)/($max) * 100 . '%) complete');
            if (!$return) {
                $i--;
            }
        }
    }

    public function makeProduct($name, $short, $description, $complete)
    {
        $product = Mage::getModel('catalog/product');
        if($product->getIdBySku(strtolower($name))) {
            return false;
        }
        $this->_notify('  Generating ' . str_pad($name, 10, " ", STR_PAD_RIGHT), false);
        $category = Mage::getModel('catalog/category');
        $catTree = $category->getTreeModel()->load();
        $catIds = $catTree->getCollection()->getAllIds();

        $cats = array_rand($catIds, 2);

        $product
            ->setWebsiteIds(array(1)) 
            ->setAttributeSetId(4) 
            ->setTypeId('simple') 
            ->setCreatedAt(strtotime('now'))
            ->setTaxClassId(4)
            ->setStatus(1)
            ->setSku(strtolower($name))
            ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) 
            ->setName($name)
            ->setDescription($description)
            ->setShortDescription($short)
            ->setNewsFromDate('06/01/2015') 
            ->setWeight(1)
            ->setPrice(10)
            ->setMediaGallery (array('images'=>array (), 'values'=>array ()))
            ->setStockData(array(
               'use_config_manage_stock' => 0, 
               'manage_stock'=>1,
               'min_sale_qty'=>1,
               'max_sale_qty'=>2,
               'is_in_stock' => 1, 
               'qty' => 999 //qty
            ))
            ->setCategoryIds($cats);

        $this->importImage('http://placehold.it/400x400', $product, strtolower($name));

        try {
            $product->save();
            $this->_notify($complete);
            return true;
        } catch (Exception $e) {
            $this->_notify("Product save failed. Trying again");
            return false;
        }
        

    }

    public function importImage($image_url, $product, $sku)
    {
        $image_type = "jpeg"; 
        $filename   = $sku.'.'.$image_type; 
        $filepath   = Mage::getBaseDir('media') . DS . 'import'. DS . $filename; 

        file_put_contents($filepath, file_get_contents(trim($image_url)));

        $mediaAttribute = array (
                'thumbnail',
                'small_image',
                'image'
        );
        $product->addImageToMediaGallery($filepath, $mediaAttribute, false, false);
    }

    public function run()
    {
        $this->generate($this->getArg('n'));

        $this->_notify('Finished');
    }

}

$shell = new Delegator_Generate;
$shell->run();