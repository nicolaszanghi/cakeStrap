<?php
App::uses('FormHelper', 'View/Helper');

class CakestrapHelper extends FormHelper {

    public function input($fieldName, $options = array()) {

        parent::setEntity($fieldName);
        $options = parent::_parseOptions($options);

        if (!isset($options['placeholder']))
            $options['placeholder'] = strip_tags(parent::_getLabel($fieldName, $options));

        if (empty($options['class'])) $options['class'] = '';
        if (empty($options['div']['class'])) $options['div']['class'] = '';

        $options['div']['class'] .= ' input '.$options['type'];

        if (in_array($options['type'], array('radio', 'checkbox'))) {
            $label = strip_tags(parent::_getLabel($fieldName, $options));
            $options['label'] = false;
            $options['before'] = '<label>';
            $options['after'] = $label.'</label>';
        } elseif (!empty($options['multiple']) && $options['multiple'] == 'checkbox') {
            $options['class'] .= ' checkbox';

        } elseif (in_array($options['type'], array('datetime', 'date', 'time'))) {

            $before_label = parent::_getLabel($fieldName, $options);
            $options['label'] = false;
            $options['div']['class'] .= ' datetimepicker input-group date';
            $data_format = array('datetime' => 'yyyy-MM-dd hh:mm:ss', 'date' => 'yyyy-MM-dd', 'time' => 'hh:mm:ss');
            $options['data-format'] = $data_format[$options['type']];
            $options['class'] .= ' form-control';
            $options['after'] = '<span class="input-group-addon">
                                    <i data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i>
                                 </span>';
            $options['type'] = 'text';

        } elseif ($options['type'] == 'search') {

            $options['label'] = false;
            $options['div']['class'] .= ' input-group';
            $options['class'] .= ' form-control';
            $options['after'] = '<span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">'.__('Search').'</button>
                                 </span>';
            $options['type'] = 'text';

        } else {
            $options['div']['class'] .= ' form-group';
            $options['class'] .= ' form-control';
        }

        $options['required'] = false;

        if (!empty($options['help-block']))
            $options['after'] .= "\n\t".'<p class="help-block">'.$options['help-block'].'</p>';

        $output = parent::input($fieldName, $options);

        if (!empty($before_label)) // label outside div
            $output = $before_label.$output;

        return $output;

    }
}