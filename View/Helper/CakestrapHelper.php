<?php
App::uses('FormHelper', 'View/Helper');

class CakestrapHelper extends FormHelper {

    public $inputClass = '';
    public $divClass = '';
    public $placeholder = true;

    public function input($fieldName, $options = array()) {

        parent::setEntity($fieldName);
        $options = parent::_parseOptions($options);

        /** class */
        if (empty($options['class'])) $options['class'] = '';
        if (@$options['div'] !== false && empty($options['div']['class'])) $options['div']['class'] = '';

        /** placeholder */
        if ($this->placeholder && !isset($options['placeholder']))
            $options['placeholder'] = strip_tags(parent::_getLabel($fieldName, $options));

        if (@$options['placeholder'] === true) {
            $options['placeholder'] = $options['label'];
            $options['label'] = false;
        }


        if (@$options['div'] !== false)
            $options['div']['class'] .= ' input '.$options['type'];

        /** radio, checkbox */
        if (in_array($options['type'], array('radio', 'checkbox'))) {
            $label = strip_tags(parent::_getLabel($fieldName, $options));
            $options['label'] = false;
            $options['before'] = '<label>';
            $options['after'] = $label.'</label>';

            /** multiple checkbox */
        } elseif (!empty($options['multiple']) && $options['multiple'] == 'checkbox') {
            $options['class'] .= ' checkbox';

            /** date/time => datetimeplicker
            /!\ DON'T FORGET TO ADD Elements/datetimpepicker_js AT THE END OF YOUR VIEW */
        } elseif (in_array($options['type'], array('datetime', 'date', 'time'))) {

            $before_label = parent::_getLabel($fieldName, $options);
            $options['label'] = false;
            $options['div']['class'] .= ' '.$options['type'].'picker input-group date form-group'; // datetimepicker, datepicker or timepicker

            $data_format = array('datetime' => 'yyyy-MM-dd hh:mm', 'date' => 'yyyy-MM-dd', 'time' => 'hh:mm');
            $options['data-format'] = $data_format[$options['type']];
            $options['class'] .= ' form-control';
            $options['after'] = '<span class="input-group-addon">
                                    <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                 </span>';
            $options['type'] = 'text';

            /** search */
        } elseif ($options['type'] == 'search') {

            $options['label'] = false;
            $options['div']['class'] .= ' input-group';
            $options['class'] .= ' form-control';
            $options['after'] = '<span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">'.__('Search').'</button>
                                 </span>';
            $options['type'] = 'text';

            /** normal input */
        } else {
            if ($options['div'] !== false)
                $options['div']['class'] .= ' form-group';
            $options['class'] .= ' form-control';
        }

        $options['required'] = false;

        /** help-block */
        if (!empty($options['help-block']))
            $options['after'] .= "\n\t".'<p class="help-block">'.$options['help-block'].'</p>';


        if (!empty($this->inputClass))
            $options['class'] .= ' '.$this->inputClass;
        if (!empty($this->divClass))
            $options['div']['class'] .= ' '.$this->divClass;

        /**
         * output
         */

        /** if local option is true display an input per languages */
        if (!empty($options['locale'])) {
            $output = '';
            if (!empty($options['placeholder'])) $placeholder = $options['placeholder'];
            $label = (empty($options['label'])) ? strip_tags(parent::_getLabel($fieldName, $options)) : $options['label'];

            // get translations, for edit
            $translations_tmp = @$this->request->data[end(explode('.', $fieldName)).'Translation'];
            if (!empty($translations_tmp)) {
                $translations = array();
                foreach ($translations_tmp as $translation)
                    $translations[$translation['locale']] = $translation['content'];
            }

            $languages = Configure::read('Config.languages');
            foreach ($languages as $k => $v) { // eng => English, fra => Français
                if (!empty($placeholder)) { $options['placeholder'] = $placeholder;
                    if (count($languages)>1) $options['placeholder'] .= ' ('.$v.')';
                }
                if (!empty($label)) { $options['label'] = $label;
                    if (count($languages)>1) $options['label'] .= ' ('.$v.')';
                }
                if (!empty($translations)) $options['value'] = $translations[$k];
                $output .= parent::input($fieldName.'.'.$k, $options);
            }


            /** normal output (without translation) */
        } else {
            $output = parent::input($fieldName, $options);
        }

        /** before label */
        if (!empty($before_label)) // label outside div
            $output = $before_label.$output;

        return $output;

    }

    /**
     * rewrite tagIsInvalid to show validation error for translated input (unset lang in entity)
     */
    public function tagIsInvalid() {
        $entity = $this->entity();
        $model = array_shift($entity);

        // 0.Model.field. Fudge entity path
        if (empty($model) || is_numeric($model)) {
            array_splice($entity, 1, 0, $model);
            $model = array_shift($entity);
        }

        $errors = array();
        if (!empty($entity) && isset($this->validationErrors[$model])) {
            $errors = $this->validationErrors[$model];
        }
        if (!empty($entity) && empty($errors)) {
            $errors = $this->_introspectModel($model, 'errors');
        }
        if (empty($errors)) {
            return false;
        }

        // added by nico
        if (!empty($entity[1]) && in_array($entity[1], array_keys(Configure::read('Config.languages'))))
            unset($entity[1]);

        $errors = Hash::get($errors, implode('.', $entity));
        return $errors === null ? false : $errors;
    }

    public function create($model = null, $options = array()) {

        if (empty($options['class']))
            $options['class'] = 'form';

        return parent::create($model, $options);

    }


    public function submit($caption = null, $options = array()) {

        if (empty($options['class']))
            $options['class'] = 'btn btn-success';

        if (empty($options['div']['class']))
            $options['div']['class'] = 'form-group';

        return parent::submit($caption, $options);

    }

}