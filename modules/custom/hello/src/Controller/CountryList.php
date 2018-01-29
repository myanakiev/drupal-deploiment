<?php

// http://user8.d8.lab/country-list/Europe

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use SameerShelavale\PhpCountriesArray\CountriesArray;

class CountryList extends ControllerBase {

    public function getTitle() {
        return $this->t('List of countries');
    }

    public function getContent($param) {
        // https://github.com/sameer-shelavale/php-countries-array
        // https://packagist.org/packages/sameer-shelavale/php-countries-array

        $countries = CountriesArray::getFromContinent('num', 'name', ucfirst($param)); // return numeric-codes->name array of countries from Europe

        $list = [
            '#theme' => 'item_list',
            '#items' => $countries,
        ];

        return [$list];
    }

}
