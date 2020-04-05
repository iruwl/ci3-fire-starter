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
                <?php echo form_label(lang('wbs input nama'), 'nama', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'nama', 'value' => set_value('nama', (isset($dt['nama']) ? $dt['nama'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('deskripsi') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('wbs input deskripsi'), 'deskripsi', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'deskripsi', 'value' => set_value('deskripsi', (isset($dt['deskripsi']) ? $dt['deskripsi'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('priority') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('wbs input priority'), 'priority', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_dropdown('priority', $dt_priority, (isset($dt['priority']) ? $dt['priority'] : 'Normal'), 'id="priority" class="form-control select2"'); ?>
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
