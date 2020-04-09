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
            <div class="form-group <?php echo form_error('server_id') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('server_apps input server_id'), 'server_id', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_dropdown('server_id', $dt_servers, (isset($dt_server_id) ? $dt_server_id : null), 'id="server_id" class="form-control select2"'); ?>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('application_id') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input application_id'), 'application_id', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('application_id', $dt_applications, (isset($dt['application_id']) ? $dt['application_id'] : null), 'id="application_id" class="form-control select2"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('kategori') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input kategori'), 'kategori', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('kategori', $dt_kategori, (isset($dt['kategori']) ? $dt['kategori'] : null), 'id="kategori" class="form-control select2"'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('app_url') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input app_url'), 'app_url', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'app_url', 'value' => set_value('app_url', (isset($dt['app_url']) ? $dt['app_url'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('app_path') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input app_path'), 'app_path', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'app_path', 'value' => set_value('app_path', (isset($dt['app_path']) ? $dt['app_path'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('app_port') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input app_port'), 'app_port', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'app_port', 'value' => set_value('app_port', (isset($dt['app_port']) ? $dt['app_port'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('app_service') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input app_service'), 'app_service', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'app_service', 'value' => set_value('app_service', (isset($dt['app_service']) ? $dt['app_service'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('api_url') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input api_url'), 'api_url', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'api_url', 'value' => set_value('api_url', (isset($dt['api_url']) ? $dt['api_url'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('git_url') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input git_url'), 'git_url', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'git_url', 'value' => set_value('git_url', (isset($dt['git_url']) ? $dt['git_url'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('status') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input status'), 'status', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('status', $dt_status, (isset($dt['status']) ? $dt['status'] : null), 'id="status" class="form-control select2"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('status_pemeliharaan') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('server_apps input status_pemeliharaan'), 'status_pemeliharaan', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('status_pemeliharaan', $dt_status_pemeliharaan, (isset($dt['status_pemeliharaan']) ? $dt['status_pemeliharaan'] : null), 'id="status_pemeliharaan" class="form-control select2"'); ?>
                    </div>
                </div>
            </div>
            <div class="form-group <?php echo form_error('keterangan') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('server_apps input keterangan'), 'keterangan', array('class' => 'control-label')); ?>
                <?php echo form_input(array('name' => 'keterangan', 'value' => set_value('keterangan', (isset($dt['keterangan']) ? $dt['keterangan'] : '')), 'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">DB Profile</h3>
                </div>
                <table class="table" id="db_profile_fields">
                    <tr>
                        <th style="vertical-align: middle;">Connection String</th>
                        <th style="vertical-align: middle;">Keterangan</th>
                        <th style="vertical-align: middle; width: 50px;"><button type="button" class="btn btn-default btn-sm" id="add_db_profile" tabindex="-1"><span class="glyphicon glyphicon-plus"></span></button></th>
                    </tr>
                    <?php foreach ($dt_db_profile as $index => $row): ?>
                        <tr class="db_profile-row" id="<?php echo $index; ?>">
                            <td><?php echo form_input(array('name' => "db_profile[$index][connection_string]", 'value' => $row['connection_string'], 'class' => 'form-control')); ?></td>
                            <td><?php echo form_input(array('name' => "db_profile[$index][keterangan]", 'value' => $row['keterangan'], 'class' => 'form-control')); ?></td>
                            <td><button type="button" class="btn btn-default btn-sm btn_remove_db_profile" id="<?php echo $index; ?>" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
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
