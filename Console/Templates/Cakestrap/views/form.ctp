<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div id="page-container" class="row">

    <div id="page-content" class="col-lg-12">

        <div class="<?php echo $pluralVar; ?> form">

            <div class="row">
                <div class="col-lg-9">
                    <h2><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
                </div>
                <div class="col-lg-3"p>
                    <div class="actions pull-right">
                        <?php if (strpos($action, 'add') === false):
                            echo "<?php echo \$this->Html->link('<i class=\"fa fa-info\"> </i>'.__('View'), array('action' => 'view', \$this->Form->value('{$modelClass}.{$primaryKey}')), array('class' => 'btn btn-info', 'escape' => false)); ?>\n";
                            echo "\t\t\t\t\t\t<?php echo \$this->Form->postLink('<i class=\"fa fa-times\"> </i>'.__('Delete'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), array('class' => 'btn btn-danger', 'escape' => false), __('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>\n";
                        endif;?>
                    </div><!-- .actions -->
                </div>
            </div>


            <?php echo "<?php echo \$this->Form->create('{$modelClass}', array('class' => 'form')); ?>\n"; ?>
            <fieldset>
                <?php foreach ($fields as $field) {
                    if (strpos($action, 'add') !== false && $field == $primaryKey) {
                        continue;
                    } elseif (!in_array($field, array('created', 'modified', 'updated'))) {
                        echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}'); ?>\n";
                        /*
                             echo "<div class=\"control-group\">\n";
                             echo "\t<?php echo \$this->Form->label('{$field}', '{$field}', array('class' => 'control-label'));?>\n";
                             echo "\t<div class=\"controls\">\n";
                             echo "\t\t<?php echo \$this->Form->input('{$field}', array('class' => 'span12')); ?>\n";
                             echo "\t</div><!-- .controls -->\n";
                             echo "</div><!-- .control-group -->\n";
                             echo "\n";
                             */
                    }
                }
                if (!empty($associations['hasAndBelongsToMany'])) {
                    foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
                        echo "\t\t\t\t<?php echo \$this->Form->input('{$assocName}');?>\n";
                    }
                }
                ?>
            </fieldset>
            <?php
            if ($action == 'delete' || $action == 'admin_delete')
                $class = 'btn-danger';
            elseif (in_array($action, array('add', 'edit', 'admin_add', 'admin_edit')))
                $class = 'btn-success';
            else
                $class = 'btn-primary';
            $submit = array('edit' => __('Save'), 'add' => __('Add'), 'delete' => __('Delete'), 'admin_edit' => __('Save'), 'admin_add' => __('Add'), 'admin_delete' => __('Delete'));
            echo "\t<?php echo \$this->Form->submit(__('$submit[$action]'), array('class' => 'btn $class', 'div' => array('class' => 'form-group'))); ?>\n";
            echo "\t\t\t<?php echo \$this->Form->end(); ?>\n";
            ?>

        </div>

    </div><!-- #page-content .col-lg-12 -->

</div><!-- #page-container .row-fluid -->
