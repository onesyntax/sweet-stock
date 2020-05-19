<?php
/**
 * Sweet Stock Module
 *
 * SweetStock class will calculate the product quality based on the sale dates.
 * php version 7.3.16
 *
 * @file     Sweet Stock Module SweetStock Class
 * @category Onesyntax
 * @package  App_SweetStock
 * @author   Dasitha Abeysinghe <dazimax@gmail.com>
 * @access   public
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App;

class SweetStock
{
    /**
     * Variable to store Product Name
     *
     * @var string
     *
     * @access public
     */
    public $name;

    /**
     * Variable to store Product Quality
     *
     * @var int
     *
     * @access public
     */
    public $quality;

    /**
     * Variable to store Sell In Days
     *
     * @var int
     *
     * @access public
     */
    public $sellIn;

    /**
     * Variable to store Sulfuras Type Products
     *
     * @var array
     *
     * @access public
     */
    public $sulfurasItemsData;

    /**
     * Variable to store Aged Brie Type Products
     *
     * @var array
     *
     * @access public
     */
    public $agedBrieItemsData;

    /**
     * Variable to store Backstage Passes Type Products
     *
     * @var array
     *
     * @access public
     */
    public $backstagePassesItemsData;

    /**
     * Variable to store Conjured Type Products
     *
     * @var array
     *
     * @access public
     */
    public $conjuredItemsData;

    /**
     * SweetStock class contruction method
     *
     * @param string $name Product Name
     * @param int    $quality Product Quality
     * @param int    $sellIn Product Sell In Days
     *
     * @access public
     */
    public function __construct($name, $quality, $sellIn)
    {
        // Add Product Names in each types
        $this->sulfurasItemsData = ['Sulfuras, Hand of Ragnaros'];
        $this->agedBrieItemsData = ['Aged Brie'];
        $this->backstagePassesItemsData = ['Backstage passes to a TAFKAL80ETC concert'];
        $this->conjuredItemsData = ['Conjured Mana Cake'];

        // Define default values
        $this->name = $name;
        $this->quality = (in_array($this->name, $this->sulfurasItemsData)) ? 80 : $quality; // "Sulfuras" is a legendary item and as such its Quality is 80 and it never alters.
        $this->sellIn = $sellIn;
    }

    /**
     * Create static object with provided values
     *
     * @param string $name Product Name
     * @param int    $quality Product Quality
     * @param int    $sellIn Product Sell In Days
     *
     * @return object
     * 
     * @access public
     */
    public static function of($name, $quality, $sellIn) 
    {
        return new static($name, $quality, $sellIn);
    }

    /**
     * Calculate the product quality based on the sell in days
     *
     * @return void
     * 
     * @access public
     */
    public function tick()
    {
        if (!in_array($this->name, $this->sulfurasItemsData)) { // Ignore Sulfuras type since it handled in __construct()
            if ($this->quality < 50 && $this->quality > 0) { // Verify Quality is between 0 (minimum) - 50 (maximum) 
                if (in_array($this->name, $this->agedBrieItemsData)) {
                    // Aged Brie Items Calculation
                    if ($this->sellIn <= 0) {
                        // Increase the quality by 1 x 2
                        $this->quality += (1 * 2);
                    } else {
                        // Increase the quality by 1
                        $this->quality += 1;
                    }
                } else if (in_array($this->name, $this->backstagePassesItemsData)) {
                    // Backstage passes Items Calculation
                    if ($this->sellIn <= 0) {
                        // Quality drops to 0 after the concert
                        $this->quality = 0; 
                    } else if ($this->sellIn <= 5) {
                        // Increase the quality by 1 x 3
                        $this->quality += (1 * 3);
                    } else if ($this->sellIn <= 10) {
                        // Increase the quality by 1 x 2
                        $this->quality += (1 * 2);
                    } else {
                        // Increase the quality by 1
                        $this->quality += 1;
                    }
                } else if (in_array($this->name, $this->conjuredItemsData)) {
                    // Conjured Items Calculation
                    if ($this->sellIn <= 0) {
                        // Reduce the quality by -1 x 4 (twice as fast as normal items)
                        $this->quality -= (1 * 4);
                    } else {
                        // Reduce the quality by -2 (twice as fast as normal items)
                        $this->quality -= 2;
                    }
                } else {
                    // Normal Items Calculation
                    if ($this->sellIn <= 0) {
                        // Reduce the quality by -1 x 2
                        $this->quality -= (1 * 2);
                    } else {
                        // Reduce the quality by -1
                        $this->quality -= 1;
                    }
                }
                // Maintain maximum quality = 50 always
                if ($this->quality > 50) {
                    $this->quality = 50;
                }
            }
            // Reduce the sellIn by -1
            $this->sellIn = $this->sellIn - 1;
        }
    }
}
