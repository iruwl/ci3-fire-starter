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
                <?php echo form_label(lang('contacts input nama'), 'nama', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'nama', 'value' => set_value('nama', (isset($dt['nama']) ? $dt['nama'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('jabatan') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('contacts input jabatan'), 'jabatan', array('class' => 'control-label')); ?>
                <?php echo form_input(array('name' => 'jabatan', 'value' => set_value('jabatan', (isset($dt['jabatan']) ? $dt['jabatan'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('nik') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('contacts input nik'), 'nik', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'nik', 'value' => set_value('nik', (isset($dt['nik']) ? $dt['nik'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('email') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('contacts input email'), 'email', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'email', 'value' => set_value('email', (isset($dt['email']) ? $dt['email'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('hp1') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('contacts input hp1'), 'hp1', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'hp1', 'value' => set_value('hp1', (isset($dt['hp1']) ? $dt['hp1'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('hp2') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('contacts input hp2'), 'hp2', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'hp2', 'value' => set_value('hp2', (isset($dt['hp2']) ? $dt['hp2'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group <?php echo form_error('keterangan') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('contacts input keterangan'), 'keterangan', array('class' => 'control-label')); ?>
                <?php echo form_input(array('name' => 'keterangan', 'value' => set_value('keterangan', (isset($dt['keterangan']) ? $dt['keterangan'] : '')), 'class' => 'form-control')); ?>
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
