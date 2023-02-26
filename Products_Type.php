<?php
require_once "outils.php";

class Products_Type
{
    // list of 6 products as it would be retrieved from a database
    const PRODUCTS = [
        [
            'id' => 0,
            'name' => 'Red Jersey',
            'description' => 'Manchester United Home Jersey, red, sponsored by Chevrolet',
            'price' => 59.99,
            'pic' => 'red_jersey.jpg',
            'qty_in_stock' => 200,
        ],
        [
            'id' => 1,
            'name' => 'White Jersey',
            'description' => 'Manchester United Away Jersey, white, sponsored by Chevrolet',
            'price' => 49.99,
            'pic' => 'white_jersey.jpg',
            'qty_in_stock' => 133,
        ],
        [
            'id' => 2,
            'name' => 'Black Jersey',
            'description' => 'Manchester United Extra Jersey, black, sponsored by Chevrolet',
            'price' => 54.99,
            'pic' => 'black_jersey.jpg',
            'qty_in_stock' => 544,
        ],
        [
            'id' => 3,
            'name' => 'Blue Jacket',
            'description' => 'Blue Jacket for cold and raniy weather',
            'price' => 129.99,
            'pic' => 'blue_jacket.jpg',
            'qty_in_stock' => 14,
        ],
        [
            'id' => 4,
            'name' => 'Snapback Cap',
            'description' => 'Manchester United New Era Snapback Cap- Adult',
            'price' => 24.99,
            'pic' => 'cap.jpg',
            'qty_in_stock' => 655,
        ],
        [
            'id' => 5,
            'name' => 'Champion Flag',
            'description' => 'Manchester United Champions League Flag',
            'price' => 24.99,
            'pic' => 'champion_league_flag.jpg',
            'qty_in_stock' => 321,
        ],
    ];

    function __construct()
    {
        // parent::__construct();
    }
    function __destruct()
    {
    }
    /**
     * retourne ka table des produits en HTML
     */
    static public function productList()
    {
        return tableHTML(self::PRODUCTS);
    }

    /**
     * retourne ka table des produits dans un catalogue, chaque
     * produit dabs yne div avec image ect ...
     */
    static public function productsCatalog()
    {
        $html = "";
        foreach (self::PRODUCTS as $un_products) {
            $html .= '<div class="product">';
            $html .= '<img src="products_images/' . $un_products['pic'] . '" alt="">';
            $html .= '<p class="name">' . $un_products['name'] . '</p>';
            $html .= '<p class="description">' . $un_products['description'] . '</p>';
            $html .= '<p class="price">' . $un_products['price'] . '</p>';
            $html .= '</div>';
        }
        return $html;
    }
}
