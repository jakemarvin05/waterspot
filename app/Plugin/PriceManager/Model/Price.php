<?php

class Price extends PriceManagerAppModel
{
    public $name = "price";

    public $validate = array(
        'slot_type' =>  array(
            'if_not_empty'=>array(
                'rule'=>'notEmpty',
                'message'=>'Please set the slot type'
            ),
            'if_valid_index'=>array(
                'rule'=>array('is_out_of_index'),
                'message'=>'Slot type is out of index.'
            )
        ),
        'price' => array(
            'rule' => 'notEmpty',
            'message' => 'Please set price.'
        ),
        'max_pax' => array(
            'rule' => 'notEmpty',
            'message' => 'Some rule have no Maximum no. of Pax set.'
        ),
        'min_pax' => array(
            'rule' => 'notEmpty',
            'message' => 'Some rule have no Minimum no. of Pax set.'
        ),
        'price_per_pax' => array(
            'rule' => 'notEmpty',
            'message' => 'Some rule have no Price per Pax set.'
        ),
        'price_per_add_hour' => array(
            'rule' => 'notEmpty',
            'message' => 'Some rule have no Price per addional hour set.'
        ),
        'max_add_hour' => array(
            'rule' => 'notEmpty',
            'message' => 'Some rule have no Maximum additional hour set'
        ),


    );

    function is_out_of_index()
    {
        $slot_type = $this->data['Price']['slot_type'];
        if ($slot_type > 3) {
            return false;
        }
        return true;
    }

    public function getPriceRange($service_id)
    {
        $criteria['conditions'] = array('Price.service_id' => $service_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $price_array = [];
            foreach ($price_rules as $price_rule) {
                $price_array[] = $price_rule['Price']['price'];
            }
            $price_range = '$' . min($price_array) . ' - $' . max($price_array);
            return $price_range;
        }
        return false;
    }

    public function getPriceRulesByServiceId($service_id)
    {
        $criteria['conditions'] = array('Price.service_id' => $service_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            return $price_rules;
        }
        return false;
    }

    public function getPriceRangePerSlot($slot_id)
    {
        $criteria['conditions'] = array('Price.slot_id' => $slot_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $price_array = [];
            foreach ($price_rules as $price_rule) {
                $price_array[] = $price_rule['Price']['price'];
            }
            $price_range = min($price_array) . ' - ' . max($price_array);
            return $price_range;
        }
        return false;
    }

    public function getPaxRangePerSlot($slot_id)
    {
        $criteria['conditions'] = array('Price.slot_id' => $slot_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $pax_array = [];
            foreach ($price_rules as $price_rule) {
                $pax_array[] = $price_rule['Price']['max_pax'];
            }
            $pax_range = min($pax_array) . ' - ' . max($pax_array);
            return $pax_range;
        }
        return false;
    }

    public function getPricePerPaxRangePerSlot($slot_id)
    {
        $criteria['conditions'] = array('Price.slot_id' => $slot_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $price_per_pax_array = [];
            foreach ($price_rules as $price_rule) {
                $price_per_pax_array[] = $price_rule['Price']['price_per_pax'];
            }
            $price_per_pax_range = min($price_per_pax_array) . ' - ' . max($price_per_pax_array);
            return $price_per_pax_range;
        }
        return false;
    }

    public function getPricePerHourRangePerSlot($slot_id)
    {
        $criteria['conditions'] = array('Price.slot_id' => $slot_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $price_array = [];
            foreach ($price_rules as $price_rule) {
                $price_array[] = $price_rule['Price']['price_per_add_hour'];
            }
            $price_range = min($price_array) . ' - ' . max($price_array);
            return $price_range;
        }
        return false;
    }

    public function getMaxAddHourRangePerSlot($slot_id)
    {
        $criteria['conditions'] = array('Price.slot_id' => $slot_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $hour_array = [];
            foreach ($price_rules as $price_rule) {
                $hour_array[] = $price_rule['Price']['max_add_hour'];
            }
            $hour_range = min($hour_array) . ' - ' . max($hour_array);
            return $hour_range;
        }
        return false;
    }


    public function getRangePerSlot($slot_id)
    {
        $criteria['conditions'] = array('Price.slot_id' => $slot_id);
        $price_rules = $this->find('all', $criteria);
        if (!empty($price_rules)) {
            $price_array = [];
            $max_pax_array = [];
            $price_per_pax_array = [];
            $price_per_hour_array = [];
            $max_add_hour_array = [];
            foreach ($price_rules as $price_rule) {
                $price_array[] = $price_rule['Price']['price'];
                $max_pax_array[] = $price_rule['Price']['max_pax'];
                $price_per_pax_array[] = $price_rule['Price']['price_per_pax'];
                $price_per_hour_array[] = $price_rule['Price']['price_per_add_hour'];
                $max_add_hour_array[] = $price_rule['Price']['max_add_hour'];
            }
            $price_range = min($price_array) != max($price_array) ? min($price_array) . ' - ' . max($price_array) : min($price_array);
            $price_per_pax_range = min($price_per_pax_array) != max($price_per_pax_array) ? min($price_per_pax_array) . ' - ' . max($price_per_pax_array) : min($price_per_pax_array);
            $price_per_hour_range = min($price_per_hour_array) != max($price_per_hour_array) ? min($price_per_hour_array) . ' - ' . max($price_per_hour_array) : min($price_per_hour_array);
            $max_pax_range = min($max_pax_array) != max($max_pax_array) ? min($max_pax_array) . ' - ' . max($max_pax_array) : min($max_pax_array);
            $max_add_hour_range = min($max_add_hour_array) != max($max_add_hour_array) ? min($max_add_hour_array) . ' - ' . max($max_add_hour_array) : min($max_add_hour_array);
            $range = array(
                'price' => $price_range,
                'max_pax' => $max_pax_range,
                'price_per_pax' => $price_per_pax_range,
                'price_per_add_hour' => $price_per_hour_range,
                'max_add_hour' => $max_add_hour_range,

            );

            return $range;
        }
        return false;
    }

}
