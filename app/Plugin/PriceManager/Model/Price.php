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
        'service_id' => array(
            'if_not_empty'=>array(
            'rule' => 'notEmpty',
            'message' => 'Service ID is not set.'
            ),
            'if_valid'=>array(
                'rule'=>array('is_int'),
                'message'=>'Service ID needs to be an integer.'
            )
        ),
        'rule_type' => array(
            'if_not_empty'=>array(
                'rule' => 'notEmpty',
                'message' => 'Rule type is not set.'
            ),
            'if_valid'=>array(
                'rule'=>array('is_string'),
                'message'=>'Rule type needs to be a string.'
            )
        ),
        'rule_key' => array(
            'rule' => 'notEmpty',
            'message' => 'A parameter was missed.'
        ),
        'rule_value' => array(
            'rule' => 'notEmpty',
            'message' => "Please fill up all the fields."
        )

    );

    function is_out_of_index(){
        $slot_type = $this->data['Price']['slot_type'];
        if (is_int($slot_type)) {
            return false;
        }
        return true;
    }

    function getAllRules($service_id){
        $criteria['conditions'] = array('Price.service_id' => $service_id);
        // Find all the rules table
        $rules = $this->find('all',$criteria);

        if(!empty($rules)){
            return $rules;
        }
        else{
            return false;
        }

    }

    function is_int(){
        $service_id = $this->data['Price']['service_id'];
        if (is_int($service_id)) {
            return false;
        }
        return true;
    }

    function is_string(){
        $rule_type = $this->data['Price']['rule_type'];
        if (!is_string($rule_type)) {
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

    public function checkIfKeyOfRuleExist($service_id,$slot_type,$rule_type,$key){
        $criteria['conditions'] = array('Price.service_id' => $service_id,'Price.slot_type'=>$slot_type,'Price.rule_key'=>$key,'Price.rule_type'=>$rule_type);
        $count = $this->find('count', $criteria);
        if ($count>0) {
            return true;
        }
        return false;
    }
    public function checkIfAllRuleAreListed($service_id,$slot_type,$rule_type){
        $criteria['conditions'] = array('Price.service_id' => $service_id,'Price.slot_type'=>$slot_type,'Price.rule_type'=>$rule_type);
        $count = $this->find('count', $criteria);
        if ($count>0) {
            return $count;
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

    public function checkIfRuleExist($service_id,$slot_type,$rule_type,$rule_key){
        $criteria['conditions'] = array(
            'Price.service_id' => $service_id,
            'Price.slot_type' => $slot_type,
            'Price.rule_type' => $rule_type,
            'Price.rule_key' => $rule_key,

            );
        $rule_count = $this->find('count', $criteria);
        if ($rule_count>0) {
            return true;
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
