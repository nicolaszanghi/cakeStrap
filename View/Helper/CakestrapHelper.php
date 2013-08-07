<?php
App::uses('FormHelper', 'View/Helper');

class CakestrapHelper extends FormHelper {

    public function input($fieldName, $options = array()) {

        parent::setEntity($fieldName);
        $options = parent::_parseOptions($options);

        if (!isset($options['placeholder']))
            $options['placeholder'] = strip_tags(parent::_getLabel($fieldName, $options));

        $options['div']['class'] = 'input '.$options['type'];

        if (in_array($options['type'], array('radio', 'checkbox'))) {
            $label = strip_tags(parent::_getLabel($fieldName, $options));
            $options['label'] = false;
            $options['before'] = '<label>';
            $options['after'] = $label.'</label>';
        } else {
            $options['div']['class'] .= ' form-group';
            $options['class'] = ' form-control';
        }

        if (!empty($options['help-block']))
            $options['after'] .= "\n\t".'<p class="help-block">'.$options['help-block'].'</p>';

        $output = parent::input($fieldName, $options);

        return $output;

    }
}