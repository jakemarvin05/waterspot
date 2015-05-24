<?php
App::uses('FormHelper', 'View/Helper');

class AdminFormHelper extends FormHelper {
    public $wrap_label = "<dt>%s</dt>";
    public $wrap_inputs = "<dd>%s</dd>";
    public $options = array(
        'div'=>false
        
    );
    public function __construct(View $View, $settings = array()) {
            parent::__construct($View, $settings);
    }
    public function input($fieldName, $options = array()) {
		$this->setEntity($fieldName);
                $options['div']=$this->options['div'];
                
                
		$options = $this->_parseOptions($options);

		$divOptions = $this->_divOptions($options);
                
		unset($options['div']);

		if ($options['type'] === 'radio' && isset($options['options'])) {
			$radioOptions = (array)$options['options'];
			unset($options['options']);
		}
                
		$label = sprintf($this->wrap_label,$this->_getLabel($fieldName, $options));
                
                
		if ($options['type'] !== 'radio') {
                    	unset($options['label']);
		}

		$error = $this->_extractOption('error', $options, null);
		unset($options['error']);

		$errorMessage = $this->_extractOption('errorMessage', $options, true);
		unset($options['errorMessage']);

		$selected = $this->_extractOption('selected', $options, null);
		unset($options['selected']);

		if ($options['type'] === 'datetime' || $options['type'] === 'date' || $options['type'] === 'time') {
			$dateFormat = $this->_extractOption('dateFormat', $options, 'MDY');
			$timeFormat = $this->_extractOption('timeFormat', $options, 12);
			unset($options['dateFormat'], $options['timeFormat']);
		}

		$type = $options['type'];
               
		$out = array('before' => $options['before'], 'label' => $label, 'between' => $options['between'], 'after' => $options['after']);
		$format = $this->_getFormat($options);
                
		unset($options['type'], $options['before'], $options['between'], $options['after'], $options['format']);

		$out['error'] = null;
		if ($type !== 'hidden' && $error !== false) {
			$errMsg = $this->error($fieldName, $error);
			if ($errMsg) {
				$divOptions = $this->addClass($divOptions, 'error');
				if ($errorMessage) {
					$out['error'] = $errMsg;
				}
			}
		}

		if ($type === 'radio' && isset($out['between'])) {
			$options['between'] = $out['between'];
			$out['between'] = null;
		}
		$out['input'] = $this->_getInput(compact('type', 'fieldName', 'options', 'radioOptions', 'selected', 'dateFormat', 'timeFormat'));
                $out['input'] = sprintf($this->wrap_inputs,$out['input']);

		$output = '';
		foreach ($format as $element) {
			$output .= $out[$element];
		}

		if (!empty($divOptions['tag'])) {
			$tag = $divOptions['tag'];
			unset($divOptions['tag']);
			$output = $this->Html->tag($tag, $output, $divOptions);
		}
		return $output;
	}
    protected function _getLabel($fieldName, $options) {
            if ($options['type'] === 'radio') {
			//return false;
            }

            $label = null;
            if (isset($options['label'])) {
                    $label = $options['label'];
            }

            if ($label === false) {
                    return false;
            }
            return $this->_inputLabel($fieldName, $label, $options);
    }
    
    
}


?>
