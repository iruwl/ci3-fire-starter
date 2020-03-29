<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<div class="header">
    <h2 style="display: inline;"><?php echo $title; ?></h2>
</div>

<?php echo form_open('', array('role' => 'form')); ?>

    <?php // hidden id ?>
    <?php if (isset($dt_id)): ?>
        <?php echo form_hidden('id', $dt_id); ?>
    <?php endif;?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo form_error('nama') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('servers input nama'), 'nama', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'nama', 'value' => set_value('nama', (isset($dt['nama']) ? $dt['nama'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('cpu') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('servers input cpu'), 'cpu', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'cpu', 'value' => set_value('cpu', (isset($dt['cpu']) ? $dt['cpu'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('storage') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('servers input memory'), 'memory', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'memory', 'value' => set_value('memory', (isset($dt['memory']) ? $dt['memory'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('storage') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('servers input storage'), 'storage', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'storage', 'value' => set_value('storage', (isset($dt['storage']) ? $dt['storage'] : '')), 'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="col-sm-6">

        </div>
    </div>

    <?php // buttons ?>
    <div class="row">
        <div class="col-sm-12">
            <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> <?php echo lang('global button save'); ?></button>
            <a class="btn btn-link" href="<?php echo $cancel_url; ?>"><?php echo lang('global button cancel'); ?></a>
        </div>
    </div>

<?php echo form_close(); ?>
