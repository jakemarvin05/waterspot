<?php

Class ActivityController extends AppController
{
    public $uses = array('Activity');
    public $components = array('VendorManager', 'MemberManager.MemberAuth', 'VendorManager.ServiceFilter');
    public $paginate = array();
    public $id = null;

    public function beforeFilter()
    {
        parent::beforeFilter();
        Configure::load('VendorManager.config');
    }

    public function getCurrentDateIndex($service_id, $selected_date)
    {
        $date = "$selected_date";

        $this->loadModel('VendorManager.VendorServiceAvailability');

        //Inputs
        if ($selected_date = null) {
            $selected_date = date("Y-m-d", strtotime("now"));
        } else {
            $selected_date = date("Y-m-d", strtotime($date));
        }

        $service = $this->VendorServiceAvailability->isDateAvailable($service_id, $selected_date);
        if ($service) {
            $recommendations = [];
            $slots = $service[0]['VendorServiceAvailability']['slots'];
            $startDate = $service[0]['VendorServiceAvailability']['start_date'];
            $endDate = $service[0]['VendorServiceAvailability']['end_date'];
            $dateComponents = explode('-', $selected_date);
            $daySelected = $dateComponents[2];
            $startDateComponents = explode('-', $startDate);
            $startDay = $startDateComponents[2];
            $endDateComponents = explode('-', $endDate);
            $endDay = $endDateComponents[2];

            if ($daySelected == $startDay) {
                return 0;
            } elseif (($daySelected - $startDay) < 3) {
                return ($daySelected - $startDay);
            } else {
                return 3;

            }
        } else {
            return 0;
        }

    }

    public function getRecommendedActivities($service_id = null, $selected_date = null)
    {
        if ($service_id == null) {
            return false;
        }
        if ($selected_date == null) {
            $selected_date = strtotime(date('Y-m-d'));
        }
        $selected_date = strtotime($selected_date);
        $one_day = 60 * 60 * 24;

        $this->loadModel('VendorManager.ServiceSlot');
        $this->loadModel('VendorManager.BookingSlot');
        $this->loadModel('VendorManager.VendorServiceAvailability');
        $this->loadModel('VendorManager.Service');
        $recommendations = [];
        $cdate = '';
        foreach (range(-3, 3) as $c) {
            $recommendSlots = [];
            $cdate = date('Y-m-d', $selected_date + $one_day * $c);
            $service = $this->VendorServiceAvailability->isDateAvailable($service_id, $cdate);
            if ($service) {
                $slots = $service[0]['VendorServiceAvailability']['slots'];
                $startDate = $service[0]['VendorServiceAvailability']['start_date'];
                $endDate = $service[0]['VendorServiceAvailability']['end_date'];
                $max_slot = $this->Service->servieDetailByService_id($service_id)['Service']['no_person'];

                $slots = substr($slots, 1, -1);
                $slots = '{' . $slots . '}';
                $slots = json_decode($slots);
                if (strtotime($endDate) >= strtotime($cdate)) {
                    foreach ($slots as $slot) {
                        if (!$this->BookingSlot->isSlotFull($service_id, $cdate, $slot->start_time, $slot->end_time, $max_slot)) {
                            array_push($recommendSlots, $slot);
                        }
                    }
                }
            }
            $recommendations[] = [
                'date' => $cdate,
                'slots' => $recommendSlots
            ];
        }

        return $recommendations;
    }


    function index($slug = null, $cart_id = null)
    {

        $this->loadModel('VendorManager.Service');
        $this->loadModel('PriceManager.Price');

        array_push(self::$css_for_layout, 'activity/activity.css');

        if (is_numeric($slug)) {
            $service_id = $slug;
        } else {
            $service_id = $this->Service->getServiceIdBySlug($slug);
            if (!$service_id) {
                throw new NotFoundException('This service does not exist.');
            }
        }

        //test
        $thisDate = isset($this->request->query['date']) ? $this->request->query['date'] : "now";
        $this->set('date', $thisDate);
        $this->set('recommendedActivities', $this->getRecommendedActivities($service_id, $thisDate));
        $this->set('currentDateIndex', $this->getCurrentDateIndex($service_id, $thisDate));

        //load model
        $this->loadModel('VendorManager.Vendor');
        $this->loadModel('VendorManager.ServiceImage');
        $this->loadModel('VendorManager.ServiceSlot');
        $this->loadModel('VendorManager.ServiceReview');
        $this->loadModel('VendorManager.Attribute');
        $this->loadModel('PriceManager.Price');
        $this->loadModel('VendorManager.ServiceAttribute');
        $this->loadModel('VendorManager.ValueAddedService');
        $this->loadModel('LocationManager.City');
        $this->loadModel('Cart');
        $this->loadModel('ServiceManager.ServiceType');
        // check service
        $no_of_booking_days = 0;
        $service_status = $this->Service->CheckServiceId($service_id);
        if ($service_status == 0) {
            throw new NotFoundException('This service is deactivated.');
        }
        // check cart valid
        if (!empty($cart_id)) {
            $cart_id_status = $this->Cart->CheckCartId($cart_id, $this->Session->id());
            if ($cart_id_status == 0) {
                throw new NotFoundException('Cart is empty or deactivated.');
            }
        }
        // load MemberAuth component
        App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
        $this->sessionKey = MemberAuthComponent::$sessionKey;
        $this->member_data = $this->Session->read($this->sessionKey);
        // Load java script and css
        array_push(self::$script_for_layout, 'login.js', 'jquery.tools.min.js', 'jquery.mousewheel.js', 'jquery.jscrollpane.min.js', 'fotorama.js', 'https://code.jquery.com/ui/1.10.3/jquery-ui.js', 'jquery.fancybox.js', 'responsive-tabs.js', /*, $this->setting['site']['jquery_plugin_url'] . 'ratings/jquery.rating.js',*/
            'http://w.sharethis.com/button/buttons.js');
        array_push(self::$css_for_layout, 'activity.css', 'jquery.jscrollpane.css', 'fotorama.css', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css', 'responsive-tabs.css'/*, $this->setting['site']['jquery_plugin_url'] . 'ratings/jquery.rating.css'*/);
        array_push(self::$css_for_layout, 'pages.css');
        self::$scriptBlocks[] = '
		$( document ).ready(function() {
			 get_service_availability();
			});
		$(function() {
		$( "#startdatepicker" ).datepicker({
		dateFormat: "' . Configure::read('Calender_format') . '",
			minDate: 0,
			onSelect:function(selectedDate){
			$( "#ActivityStartDate" ).val(selectedDate);
			$( "#ActivityEndDate" ).datepicker( "option", "minDate", selectedDate );
			get_service_availability();
			$(this).change();
			 }
		}
		);
		$( "#enddatepicker" ).datepicker({
			dateFormat: "' . Configure::read('Calender_format') . '",
			minDate: 0,
			onSelect:function(selectedDate){
			$( "#ActivityEndDate" ).val(selectedDate);
			$( "#startdatepicker" ).datepicker( "option", "maxDate", selectedDate );
			$(this).change();
		  }
		});

		// for ajax load slots
			$("#ActivityNoParticipants,#ActivityStartDate , #ActivityEndDate").bind("change",function(){
				var participant_no=$("#ActivityNoParticipants").val();
				if(0>=participant_no){
					$( "#ActivityNoParticipants").val(1);
					//alert("Participant no should be greater than zero");
				}
				get_service_availability();
			});

		});
		$( document ).ready(function() {
		 $( "#ActivityStartDate" ).click();
		});
		$(document).ready(function() {
			$(\'.fancybox\').fancybox();
		});
		stLight.options({publisher: "5d0165c7-537f-40b4-8ecd-7ef5d49cceb2"});';
        $service_detail = array();
        $service_detail = $this->Service->servieDetailByService_id($service_id);

        // get vendor service details

        if (!empty($service_detail)) {

            $vendor_details = $this->Vendor->vendorDetailId($service_detail['Service']['vendor_id']);
            $vendor_details['Vendor']['rating'] = $this->ServiceReview->getVendorRatings($service_detail['Service']['vendor_id']);
            //get services

            $vendor_details['Service'] = $this->Service->serviceListVendorById($vendor_details['Vendor']['id']);
            // get related similar service

            if (!empty($service_detail['Service']['vendor_id'])) {
                $related_services = array();
                $related_services = $this->Service->findRelatedServiceByVendor($service_detail['Service']['vendor_id'], $service_detail['Service']['id']);
                if (!empty($related_services)) {
                    foreach ($related_services as $related_service) {
                        $related_service['Service']['image'] = $this->ServiceImage->getOneimageServiceImageByservice_id($related_service['Service']['id']);
                        $service_detail['VendorService'][] = $related_service;
                    }
                }
            }
        }
        // get service review
        $service_detail['Review'] = $this->ServiceReview->getServiceReviewByservice_id($service_id);
        $service_detail['Rating'] = $this->ServiceReview->getServiceRatings($service_id);
        $service_detail['image'] = $this->ServiceImage->getServiceImageByservice_id($service_id);
        $service_detail['location_name'] = $this->City->getLocationListCityID($service_detail['Service']['location_id']);
        $service_detail['service_type'] = $this->ServiceType->getServiceTypeNameById($service_detail['Service']['service_type_id']);
        $booking_count = $this->Cart->CountBookingByServiceId($service_id);
        $this->set('booking_count', $booking_count);
        // invite friend after add card table
        $cart_details = array();
        $cart_slots = array();
        if (!empty($cart_id)) {
            $criteria = array();
            $criteria['fields'] = array('Cart.*', 'Service.service_title');
            $criteria['joins'] = array(
                array(
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => array('Service.id = Cart.service_id')
                )
            );
            $criteria['conditions'] = array('Cart.session_id' => $this->Session->id(), 'Cart.id' => $cart_id);
            $criteria['order'] = array('Cart.id DESC');
            $cart_details = $this->Cart->find('first', $criteria);


            $cart_details['Cart']['image'] = $this->ServiceImage->getOneimageServiceImageByservice_id($cart_details['Cart']['service_id']);
            $cart_slots = json_decode($cart_details['Cart']['slots'], true);
            if (!empty($cart_slots)) {
                $cart_details['Cart']['slots'] = $cart_slots['Slot'];
            }
            // get Value added Service
            $value_added_services = array();
            if (!empty($cart_details['Cart']['service_id'])) {
                $value_added_services = $this->ValueAddedService->getValueaddedServiceByservice_id($cart_details['Cart']['service_id']);
            }

            // assign value added services
            $cart_details['Cart']['value_added_services'] = $value_added_services;
            $diff = abs(strtotime($cart_details['Cart']['end_date']) - strtotime($cart_details['Cart']['start_date']));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $no_of_booking_days = (floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24))) + 1;

        }
        // assign search value in input box
        $search_detais = $this->Session->read('Activity');
        if (!empty($search_detais)) {
            $this->request->data['Activity']['start_date'] = $this->Session->read('Activity.start_date');
            $this->request->data['Activity']['end_date'] = $this->Session->read('Activity.end_date');
            $this->request->data['Activity']['no_participants'] = $this->Session->read('Activity.no_participants');
        }
        $this->Session->delete('Activity');
        $this->breadcrumbs[] = array(
            'url' => Router::url('/'),
            'name' => 'Home'
        );
        $this->breadcrumbs[] = array(
            'url' => Router::url(array('controller' => 'activity', 'action' => 'activities', 'vendor_id', $service_detail['Service']['service_type_id'])),
            'name' => $service_detail['service_type']
        );
        $this->breadcrumbs[] = array(
            'url' => Router::url('/'),
            'name' => ucfirst($service_detail['Service']['service_title'])
        );

        // set page title and description
        $this->title_for_layout = ucfirst($service_detail['Service']['service_title']);
        //$this->metakeyword = ucfirst($service_detail['Service']['description']);
        $this->metadescription = ucfirst(strip_tags($service_detail['Service']['description']));
        $this->set('no_of_booking_days', $no_of_booking_days);
        $this->set('cart_id', $cart_id);
        $this->set('service_id', $service_id);
        $this->set('cart_details', $cart_details);
        $this->set('vendor_details', $vendor_details);
        $this->set('service_detail', $service_detail);
        $this->set('member_id', $this->member_data['MemberAuth']['id']);

        $this->set('web_title', 'Waterspot Activity | ' . $this->title_for_layout);
        $this->set('web_type', 'website');
        $this->set('web_url', 'http://www.waterspot.com.sg' . $_SERVER['REQUEST_URI']);
        $this->set('web_image', $service_detail['image'][0]);
        $this->set('web_site_name', 'Waterspot Activity | ' . $this->title_for_layout);
        $price_range = $this->ServiceSlot->find('first', ['conditions' => ['service_id' => $service_id], 'fields' => ['MAX(price) as maxprice', 'MIN(price) as minprice']]);
        $this->set('max_price', $price_range[0]['maxprice']);
        $this->set('min_price', $price_range[0]['minprice']);


        // attributes
        // this is how we call the attributes
        $attribute_list = $this->ServiceAttribute->find('all', ['conditions' => ['service_id' => $service_id]]);
        $attributes = [];
        foreach ($attribute_list as $attr) {
            $attribute = [];
            $attribute_detail = $this->Attribute->find('first', ['conditions' => ['id' => $attr['ServiceAttribute']['attribute_id']]]);
            if (!$attribute_detail) {
                $remove_id = $this->ServiceAttribute->find('first', ['conditions' => ['service_id' => $service_id, 'attribute_id' => $attr['ServiceAttribute']['attribute_id']]]);
                $this->ServiceAttribute->delete($remove_id['ServiceAttribute']['id']);
                continue;
            }
            $attribute['name'] = $attribute_detail['Attribute']['name'];
            $attribute['type'] = $attribute_detail['Attribute']['type'] == 1 ? 'Amenity' : ($attribute_detail['Attribute']['type'] == 2 ? 'Included' : ($attribute_detail['Attribute']['type'] == 3 ? 'Extra' : 'Detail'));
            $attribute['has_input'] = $attribute_detail['Attribute']['has_input'];
            $attribute['icon_class'] = $attribute_detail['Attribute']['icon_class'] ? $attribute_detail['Attribute']['icon_class'] : 'fa fa-check';
            $attribute['value'] = $attr['ServiceAttribute']['value'];
            $attributes[] = $attribute;
        }
        $amenities = [];
        $included = [];
        $extra = [];
        $details = [];
        foreach ($attributes as $attr) {
            if ($attr['type'] == 'Amenity') {
                $amenities[] = $attr;
            } elseif ($attr['type'] == 'Included') {
                $included[] = $attr;
            } elseif ($attr['type'] == 'Extra') {
                $extra[] = $attr;
            } else {
                $details[] = $attr;
            }
        }
        $rules_of_service = $this->Price->getAllRules($service_id);
        $rule_object = array();
        $pax = [];
        $hour = [];
        $weekday_rules = array();
        $weekend_rules = array();
        $special_rules = array();
        foreach ($rules_of_service as $rule) {
            // extract the max pax
            if ($rule['Price']['rule_key'] == 'max pax') {
                $pax[] = $rule['Price']['rule_value'];

            }
            if ($rule['Price']['rule_key'] == 'max additional hour') {
                $hour[] = $rule['Price']['rule_value'];
            }
            switch ($rule['Price']['slot_type']) {
                case 1:
                    $weekday_rules[$rule['Price']['rule_key']] = $rule['Price']['rule_value'];
                    break;
                case 2:
                    $weekend_rules[$rule['Price']['rule_key']] = $rule['Price']['rule_value'];
                    break;
                case 3:
                    $special_rules[$rule['Price']['rule_key']] = $rule['Price']['rule_value'];
                    break;
                default:
                    break;
            }

        }


        $rule_object['max_add_hour'] = $hour ? max($hour) : 0;
        $rule_object['max_pax'] = $pax ? (max($pax) + $service_detail['Service']['num_pax_included']) : 0;
        $rule_object['rules']['weekday_rules'] = $weekday_rules;
        $rule_object['rules']['weekend_rules'] = $weekend_rules;
        $rule_object['rules']['special_rules'] = $special_rules;

        $header = $this->ServiceType->find('first', ['conditions' => ['id' => $service_detail['Service']['service_type_id']]]);
        $this->set('header', $header['ServiceType']['header']);
        $this->set('amenities', $amenities);
        $this->set('included', $included);
        $this->set('extra', $extra);
        $this->set('details', $details);
        $this->set('rule_object', $rule_object);
        $this->set('rule_object_json', json_encode($rule_object));

        //end of calling the attributes
    }


    function ajax_get_availbility_range()
    {
        $this->layout = '';
        $this->loadModel('VendorManager.ServiceSlot');
        $this->loadModel('VendorManager.Service');
        $this->loadModel('VendorManager.BookingSlot');
        $this->loadModel('VendorManager.VendorServiceAvailability');
        $this->loadModel('BookingParticipate');

        if (!empty($_POST)) {
            //Inputs
            $selected_date = date("Y-m-d", strtotime($_POST['start_date']));
            $capacity = isset($_POST['no_participants']) ? $_POST['no_participants'] : 1;
            $service_id = $_POST['service_id'];

            $this->set('service_price', $this->Service->find('first', ['conditions' => ['Service.id' => $service_id]])['Service']['service_price']);
            $service_details = $this->Service->find('first', ['conditions' => ['Service.id' => $service_id]])['Service'];
            $this->set('service_details', $service_details);
            // print_r($service_details);die;
            $service = $this->VendorServiceAvailability->isDateAvailable($service_id, $selected_date);

            if (count($service) !== 0) {
                $service = $service[0]['VendorServiceAvailability'];

                $slots = json_decode('{' . substr($service['slots'], 1, -1) . '}');

                $new_service_slots = $this->VendorServiceAvailability->getSlotByServiceID($_POST);
                $new_service_slots_now = [];

                foreach ($new_service_slots as $key => $service_slot) {
                    $slot_index_new = [];
                    foreach ($service_slot['slotindex'] as $slotkey => $slot_index) {
                        // filter already passed time
                        $current_time = time() + 60 * 60; // with 1hr margin
                        $check_time = strtotime($service_slot['start_date'] . ' ' . $slot_index->start_time);
                        if ($current_time > $check_time)
                            continue;

                        $criteria = [
                            'conditions' => [
                                'ServiceSlot.service_id' => $service_id,
                                'ServiceSlot.start_time' => $slot_index->start_time,
                                'ServiceSlot.end_time' => $slot_index->end_time,
                                'ServiceSlot.price' => $slot_index->price,
                            ],
                        ];
                        $slot = $this->ServiceSlot->find('first', $criteria);


                        $new = new stdClass;
                        $new->id = $slot['ServiceSlot']['id'];
                        $new->service_id = $slot['ServiceSlot']['service_id'];
                        $new->start_time = $slot['ServiceSlot']['start_time'];
                        $new->end_time = $slot['ServiceSlot']['end_time'];
                        $new->slot_type = $slot['ServiceSlot']['slot_type'];
                        $new->price = $slot['ServiceSlot']['price'];
                        $new->fire_sales_price = isset($slot['ServiceSlot']['fire_sales_price']) ? $slot['ServiceSlot']['fire_sales_price'] : null;
                        $new->fire_sales_day_margin = isset($slot['ServiceSlot']['fire_sales_day_margin']) ? $slot['ServiceSlot']['fire_sales_day_margin'] : null;
                        $current_booked_count = $this->BookingSlot->usedSlotCount($service_id, $selected_date, $new->start_time, $new->end_time);
                        $new->available_count = $service_details['no_person'] - $current_booked_count;
                        $new->current_booked_count = $current_booked_count;

                        // commented the line of code below to show booked slot
                        //if ($service_details['is_private'] == 1 && $current_booked_count > 0) continue;

                        if ($capacity <= $new->available_count) {
                            // set a booked flag to false to be used in rendering
                            $new->booked = false;
                        } else {
                            // set a booked flag to true to be used in rendering
                            $new->booked = true;

                        }
                        // assign the new properties
                        $slot_index_new[] = $new;
                    }

                    $new_service_slots_now[$key]['service_id'] = $service_slot['service_id'];
                    $new_service_slots_now[$key]['start_date'] = $service_slot['start_date'];
                    $new_service_slots_now[$key]['end_date'] = $service_slot['end_date'];
                    $new_service_slots_now[$key]['slotindex'] = $slot_index_new;

                }

                if (!empty($new_service_slots)) {
                    $this->set('service_slots', $new_service_slots_now);

                } else {
                    $dates = [];
                    $one_day = 60 * 60 * 24;
                    $start = strtotime($service['start_date']);
                    $end = strtotime($service['end_date']);
                    $date = $start;
                    while ($date != $end) {
                        $dates[] = date('Y-m-d', $date);
                        $date = $date + $one_day;
                    }

                    $key = array_search($selected_date, $dates);
                    $recommended = []; //sets as array

                    $before_dates = array_reverse(array_slice($dates, 0, $key));
                    foreach ($before_dates as $date) {
                        foreach ($slots as $slot) {
                            $data = $this->BookingSlot->isSlotBooked($service_id, $date, $slot->start_time, $slot->end_time);
                            if (!$data) { // almost same function as the availablility of selected date.
                                $recommended[] = $data;
                            }
                        }
                        if (count($recommended) === 3) {
                            break;
                        }
                    }
                    $recommended = array_reverse($recommended);

                    $after_dates = array_slice($dates, $key + 1);
                    foreach ($after_dates as $date) {
                        foreach ($slots as $slot) {
                            $time = explode('_', $slot);
                            $start_time = $time[0];
                            $end_time = $time[1];
                            $data = $this->BookingSlot->isSlotBooked($service_id, $date, $start_time, $end_time);
                            if (!$data) {
                                $recommended[] = $data;
                            }
                        }
                        if (count($recommended) === 6) {
                            break;
                        }
                    }
                    // return json_encode($recommended);
                    $this->set('recommended_dates', $recommended);
                }
            }
        }
    }

    function add_to_card()
    {
        $this->autoRendor = false;

        $this->loadModel('VendorManager.Service');
        $this->loadModel('VendorManager.ServiceImage');
        $this->loadModel('Cart');
        $this->loadModel('VendorManager.Vendor');
        App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
        $this->sessionKey = MemberAuthComponent::$sessionKey;
        $this->member_data = $this->Session->read($this->sessionKey);
        if (!empty($this->request->data) && $this->validation()) {
            //check Guest check email or
            $guest_email = $this->Session->read('Guest_email');

            $data['Cart'] = $this->request->data['Activity'];
            $slot_data = array();
            //get price of service  by id
            $service_detail = $this->Service->servieDetailByService_id($data['Cart']['service_id']);
            $service_price = (!empty($service_detail['Service']['service_price'])) ? $service_detail['Service']['service_price'] : 0;
            if (!empty($this->request->data['Activity']['slots'])) {
                foreach ($this->request->data['Activity']['slots'] as $key => $slot) {
                    // if slot is not selected then contiue
                    if ($slot == 0) {
                        continue;
                    }
                    $slot_booking_details = explode('_', $slot);
                    // slot attributes
                    $slot_booking_detail = array();
                    foreach ($slot_booking_details as $slot_key => $slot_attb) {
                        if ($slot_key == 0)
                            $slot_booking_type = 'slot_date';
                        if ($slot_key == 1)
                            $slot_booking_type = 'service_id';
                        if ($slot_key == 2)
                            $slot_booking_type = 'slot_id';
                        if ($slot_key == 3)
                            $slot_booking_type = 'start_time';
                        if ($slot_key == 4)
                            $slot_booking_type = 'end_time';
                        if ($slot_key == 5)
                            $slot_booking_type = 'price';
                        //
                        $slot_booking_detail[$slot_booking_type] = $slot_attb;
                    }

                    // check slot booking
                    $slotdata = array();
                    $slotdata = $slot_booking_detail;

                    $slotdata['no_participants'] = isset($this->request->data['Activity']['no_participants']) ? $this->request->data['Activity']['no_participants'] : $this->request->data['Activity']['no_of_pax'];
                    $slotdata['no_of_pax'] = (isset($this->request->data['Activity']['no_of_pax']) ? $this->request->data['Activity']['no_of_pax'] : null);
                    $slotdata['add_hour'] = (isset($this->request->data['Activity']['add_hour']) ? $this->request->data['Activity']['add_hour'] : null);
                    $booking_status = $this->ServiceFilter->slot_filter($slotdata);

                    if (empty($booking_status)) {
                        $this->Session->setFlash('Some slots have been booked. Please select another slots.', 'default', '', 'error');
                        $this->redirect($this->referer());
                        throw new NotFoundException('Some slots have been booked. Please select another slots.');
                    }
                    $slot_data['Slot'][$key] = $slot_booking_detail;
                }
                // calculate no of slot price
                $no_of_slots = count($slot_data['Slot']);
                // $total_slot_price=($no_of_slots >0 &&  $service_price>0)?$service_price*$no_of_slots:0;
                $total_slot_price = 0;

                foreach ($slot_data['Slot'] as $slot) {
                    $total_slot_price = $total_slot_price + $slot['price'];
                }
                $full_day_status = 0;
            } else {
                $diff = abs(strtotime($data['Cart']['end_date']) - strtotime($data['Cart']['start_date']));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = (floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24))) + 1;
                if ($days > 0) {
                    $full_day_status = 1;
                    $service_price = $service_detail['Service']['full_day_amount'];
                    $total_slot_price = $service_detail['Service']['full_day_amount'] * $days;
                }
            }
            if (!empty($this->member_data['MemberAuth']['id'])) {
                $data['Cart']['member_id'] = $this->member_data['MemberAuth']['id'];
            }

            // service image by service id
            $service_image = $this->ServiceImage->getOneimageServiceImageByservice_id($data['Cart']['service_id']);
            App::uses('ImageResizeHelper', 'View/Helper');
            $ImageComponent = new ImageResizeHelper(new View());
            $path = WWW_ROOT . 'img' . DS . 'service_images' . DS;
            $siteurl = $this->setting['site']['site_url'];
            $imgArr = array('source_path' => $path, 'img_name' => $service_image, 'width' => 80, 'height' => 80);
            $image_name = $siteurl . "/img/" . $ImageComponent->ResizeImage($imgArr);
            if (isset($this->request->data['Activity']['no_of_pax'])) {
                $data['Cart']['no_of_pax'] = $this->request->data['Activity']['no_of_pax'];
                $data['Cart']['no_participants'] = $this->request->data['Activity']['no_of_pax'];

            }
            $data['Cart']['additional_hour'] = isset($this->request->data['Activity']['add_hour']) ? $this->request->data['Activity']['add_hour'] : null;
            $data['Cart']['booking_date'] = date('Y-m-d H:i:s');
            $data['Cart']['price'] = $service_price;
            $data['Cart']['total_amount'] = $total_slot_price;
            $data['Cart']['full_day_status'] = $full_day_status;
            $data['Cart']['vendor_id'] = $service_detail['Service']['vendor_id'];
            $data['Cart']['slug'] = $service_detail['Service']['slug'];
            $data['Cart']['service_title'] = $service_detail['Service']['service_title'];
            $data['Cart']['start_date'] = date('Y-m-d', strtotime($data['Cart']['start_date']));
            $data['Cart']['end_date'] = date('Y-m-d', strtotime($data['Cart']['end_date']));
            $data['Cart']['time_stamp'] = date('Y-m-d H:i:s');
            $data['Cart']['mail_image'] = $image_name;
            $data['Cart']['location_id'] = $service_detail['Service']['location_id'];
            $data['Cart']['guest_email'] = $guest_email;
            $data['Cart']['session_id'] = $this->Session->id();
            // add vendor details
            $data1 = $this->Vendor->vendorDetailId($service_detail['Service']['vendor_id']);
            if (!empty($data1)) {
                $data['Cart']['vendor_name'] = $data1['Vendor']['fname'] . " " . $data1['Vendor']['lname'];
                $data['Cart']['vendor_email'] = $data1['Vendor']['email'];
                $data['Cart']['vendor_phone'] = $data1['Vendor']['phone'];
            }
            if (!empty($slot_data)) {
                $data['Cart']['slots'] = json_encode($slot_data);
            }

            $this->Cart->create();
            $this->Cart->save($data);


            $cart_id = $this->Cart->id;
            //$this->Session->setFlash(__('Activity has been added successfully'));
            $this->redirect(array('plugin' => false, 'controller' => 'activity', 'action' => 'book', $data['Cart']['slug'], $cart_id));
        } else {
            $this->Session->setFlash('Please select correct date.', 'default', '', 'error');
            $this->redirect(Controller::referer());
        }
    }

    function validation()
    {
        if (empty($this->request->data['Activity']['end_date'])) {
            $this->request->data['Activity']['end_date'] = $this->request->data['Activity']['start_date'];
        }
        $this->Activity->set($this->request->data);
        $result = array();
        if ($this->Activity->validates()) {
            $result['error'] = 0;
        } else {
            $result['error'] = 1;
        }
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $result['errors'] = $this->Activity->validationErrors;
            $errors = array();
            foreach ($result['errors'] as $field => $data) {
                $errors['Activity' . Inflector::camelize($field)] = array_pop($data);
            }
            $result['errors'] = $errors;
            echo json_encode($result);
            return;
        }
        return (int)($result['error']) ? 0 : 1;
    }

    function activities($vendor_id = null, $service_type_id = null, $sort_by_price = null, $sort_by_review = null)
    {
        // load model
        array_push(self::$css_for_layout, 'pages.css');
        $this->loadModel('ServiceManager.ServiceType');
        $this->loadModel('VendorManager.Service');
        $this->loadModel('VendorManager.Vendor');
        $this->loadModel('VendorManager.ServiceImage');
        $this->loadModel('VendorManager.ServiceReview');
        array_push(self::$script_for_layout, array('jquery.contenthover.min.js', $this->setting['site']['jquery_plugin_url'] . 'ratings/jquery.rating.js'));
        array_push(self::$css_for_layout, array($this->setting['site']['jquery_plugin_url'] . 'ratings/jquery.rating.css'));
        // searching list
        $service_name = '';
        $conditions = array();
        $vendor_list = array();
        $service_type_list = array();
        if ($vendor_id != null && $vendor_id != 'vendor_id') {
            $this->request->data['Search']['vendor_list'] = $vendor_id;
            $conditions['Service.vendor_id ='] = $vendor_id;
        }
        if ($service_type_id != null && $service_type_id != 'service_type') {
            $this->request->data['Search']['service_type_list'] = $service_type_id;
            $conditions['Service.service_type_id ='] = $service_type_id;
        }
        if ($sort_by_price != null && $sort_by_price != 'sortbyprice') {
            $price_range = explode('-', $sort_by_price);
            $this->request->data['Search']['sort_price'] = $sort_by_price;
            $conditions[] = array('OR' => array(
                array('Service.service_price BETWEEN ? AND ?' => array($price_range[0], $price_range[1]))));
        }
        $conditions[] = array('AND' => array('Vendor.active' => 1, 'Service.status' => 1), 'OR' => array('Vendor.payment_status' => 1, 'Vendor.account_type' => 0));
        $this->paginate = array();
        $subQuery = "(SELECT AVG(ifnull((`ServiceReview`.`rating`), 0)) FROM service_reviews AS `ServiceReview` WHERE `ServiceReview`.`service_id` = `Service`.`id` and `ServiceReview`.`status` = 1 GROUP BY `ServiceReview`.`service_id`) AS \"rating\" ";
        $this->paginate['fields'] = array('Service.id', 'Service.slug', 'Service.service_title', 'Service.service_price', 'Service.description', $subQuery);
        $this->paginate['joins'] = array(
            array(
                'table' => 'vendors',
                'alias' => 'Vendor',
                'type' => 'inner',
                'conditions' => array('Vendor.id = Service.vendor_id')
            ),
            array(
                'table' => 'cities',
                'alias' => 'City',
                'type' => 'LEFT',
                'conditions' => array('City.id =Service.location_id')
            ),
            array(
                'table' => 'vendor_service_availabilities',
                'alias' => 'VendorServiceAvailability',
                'type' => 'LEFT',
                'conditions' => array('VendorServiceAvailability.service_id =Service.id')
            ),

        );
        $this->paginate['conditions'][] = $conditions;
        $this->paginate['limit'] = Configure::read('Activiy.Limit');
        $this->paginate['group'] = array('Service.id');
        $this->paginate['order'] = array('Service.id' => 'DESC');
        if ($sort_by_review != null && $sort_by_review != 'sortbyreview') {
            $this->request->data['Search']['sort_review'] = $sort_by_price;
            if (intval($sort_by_review) == 1) {
                $this->paginate['order'] = "rating DESC";
            } else {
                $this->paginate['order'] = "rating ASC";
            }
        }
        $activity_service_list = $this->paginate('Service');
        $new_activity_service_list = array();
        foreach ($activity_service_list as $key => $service_list) {
            $service_list['image'] = $this->ServiceImage->getOneimageServiceImageByservice_id($service_list['Service']['id']);
            $service_list['rating'] = (round($service_list[0]['rating']));
            $service_list['slug'] = $service_list['Service']['slug'];
            $new_activity_service_list[$key] = $service_list;
        }
        //pr($new_activity_service_list);
        //all service type listing.
        $service_type_list = Cache::read('cake_service_list');
        if (empty($service_type_list)) {
            $this->loadModel('ServiceManager.ServiceType');
            $service_type_list = $this->ServiceType->find('list', array('fields' => array('ServiceType.id', 'ServiceType.name'), 'conditions' => array('ServiceType.status' => 1), 'order' => array('ServiceType.reorder ASC')));
            Cache::write('cake_service_list', $service_type_list);
        }
        // all vendor list
        $vendor_list = $this->Vendor->vendorList();
        $this->set('service_type_list', $service_type_list);
        $this->set('vendor_list', $vendor_list);
        $this->set('activity_service_list', $new_activity_service_list);
        // set css and script
        $this->breadcrumbs[] = array(
            'url' => Router::url('/'),
            'name' => 'Home'
        );
        $this->breadcrumbs[] = array(
            'url' => Router::url(array('controller' => 'activity', 'action' => 'index')),
            'name' => "Activities"
        );
        $this->set('sort_by_price', $sort_by_price);
        if ($this->request->is('ajax')) {
            $this->layout = '';
            $this->Render('ajax_activities');
        }
        $this->loadModel('ContentManager.Page');
        $page = $this->Page->read(null, 4);
        if (!empty($page['Page']['page_title'])) {
            $this->title_for_layout .= ": " . $page['Page']['page_title'];
        }
        if (!empty($page['Page']['page_metakeyword'])) {
            $this->metakeyword = $page['Page']['page_metakeyword'];
        }
        if (!empty($page['Page']['page_metadescription'])) {
            $this->metadescription = $page['Page']['page_metadescription'];
        }
    }

    public function ajax_get_recommended_dates()
    {
        $this->layout = '';
        $thisDate = isset($_POST['start_date']) ? $_POST['start_date'] : "now";
        $service_id = $_POST['service_id'];

        $this->set('date', $thisDate);
        $this->set('recommendedActivities', $this->getRecommendedActivities($service_id, $thisDate));
        $this->set('currentDateIndex', $this->getCurrentDateIndex($service_id, $thisDate));
    }
}

?>
