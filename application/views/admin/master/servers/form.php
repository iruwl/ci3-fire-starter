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
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('os') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input os'), 'os', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'os', 'value' => set_value('os', (isset($dt['os']) ? $dt['os'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('processor') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input processor'), 'processor', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'processor', 'value' => set_value('processor', (isset($dt['processor']) ? $dt['processor'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('memory') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input memory'), 'memory', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'memory', 'value' => set_value('memory', (isset($dt['memory']) ? $dt['memory'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('storage') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input storage'), 'storage', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name' => 'storage', 'value' => set_value('storage', (isset($dt['storage']) ? $dt['storage'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('owner') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input owner'), 'owner', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('owner', $dt_owner, (isset($dt['owner']) ? $dt['owner'] : 'Milik Klien'), 'id="owner" class="form-control select2"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('owned_by_client') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input owned_by_client'), 'owned_by_client', array('class' => 'control-label')); ?>
                        <?php echo form_dropdown('owned_by_client', $dt_owned_by_client, (isset($dt['owned_by_client']) ? $dt['owned_by_client'] : 'N/A'), 'id="owned_by_client" class="form-control select2"'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('owned_by_other') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input owned_by_other'), 'owned_by_other', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'owned_by_other', 'value' => set_value('owned_by_other', (isset($dt['owned_by_other']) ? $dt['owned_by_other'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('location') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('servers input location'), 'location', array('class' => 'control-label')); ?>
                        <?php echo form_input(array('name' => 'location', 'value' => set_value('location', (isset($dt['location']) ? $dt['location'] : '')), 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group <?php echo form_error('keterangan') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('servers input keterangan'), 'keterangan', array('class' => 'control-label')); ?>
                <?php echo form_input(array('name' => 'keterangan', 'value' => set_value('keterangan', (isset($dt['keterangan']) ? $dt['keterangan'] : '')), 'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ports</h3>
                </div>
                <table class="table" id="port_fields">
                    <tr>
                        <th style="vertical-align: middle;">Port</th>
                        <th style="vertical-align: middle;">Keterangan</th>
                        <th style="vertical-align: middle; width: 50px;"><button type="button" class="btn btn-default btn-sm" id="add_port" tabindex="-1"><span class="glyphicon glyphicon-plus"></span></button></th>
                    </tr>
                    <?php foreach ($dt_ports as $index => $row): ?>
                        <tr class="port-row" id="<?php echo $index; ?>">
                            <td><?php echo form_input(array('name' => "ports[$index][port]", 'value' => $row['port'], 'class' => 'form-control')); ?></td>
                            <td><?php echo form_input(array('name' => "ports[$index][keterangan]", 'value' => $row['keterangan'], 'class' => 'form-control')); ?></td>
                            <td><button type="button" class="btn btn-default btn-sm btn_remove_port" id="<?php echo $index; ?>" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Networks</h3>
                </div>
                <table class="table" id="network_fields">
                    <tr>
                        <th style="vertical-align: middle;">Interface</th>
                        <th style="vertical-align: middle;">IP Address</th>
                        <th style="vertical-align: middle;">Keterangan</th>
                        <th style="vertical-align: middle; width: 50px;"><button type="button" class="btn btn-default btn-sm" id="add_network" tabindex="-1"><span class="glyphicon glyphicon-plus"></span></button></th>
                    </tr>
                    <?php foreach ($dt_networks as $index => $row): ?>
                        <tr class="network-row" id="<?php echo $index; ?>">
                            <td><?php echo form_input(array('name' => "networks[$index][interface]", 'value' => $row['interface'], 'class' => 'form-control')); ?></td>
                            <td><?php echo form_input(array('name' => "networks[$index][ip]", 'value' => $row['ip'], 'class' => 'form-control', "pattern" => "((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$", "title" => "IP Address")); ?></td>
                            <td><?php echo form_input(array('name' => "networks[$index][keterangan]", 'value' => $row['keterangan'], 'class' => 'form-control')); ?></td>
                            <td><button type="button" class="btn btn-default btn-sm btn_remove_network" id="<?php echo $index; ?>" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Users</h3>
                </div>
                <table class="table" id="user_fields">
                    <tr>
                        <th style="vertical-align: middle;">User</th>
                        <th style="vertical-align: middle;">Password</th>
                        <th style="vertical-align: middle; width: 50px;"><button type="button" class="btn btn-default btn-sm" id="add_user" tabindex="-1"><span class="glyphicon glyphicon-plus"></span></button></th>
                    </tr>
                    <?php foreach ($dt_users as $index => $row): ?>
                        <tr class="user-row" id="<?php echo $index; ?>">
                            <td><?php echo form_input(array('name' => "users[$index][user]", 'value' => $row['user'], 'class' => 'form-control')); ?></td>
                            <td><?php echo form_input(array('name' => "users[$index][password]", 'value' => $row['password'], 'class' => 'form-control')); ?></td>
                            <td><button type="button" class="btn btn-default btn-sm btn_remove_user" id="<?php echo $index; ?>" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>

    <?php if (!empty($dt_id)): ?>
        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
            <li role="presentation" class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <h4 style="font-size: inherit; font-weight: bold; margin-top: 3px; margin-bottom: 3px;">
                        Aplikasi <?php echo count($dt_applications) ? '(' . count($dt_applications) . ')' : '' ?> <span class="caret"></span>
                    </h4>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)"
                            onclick="window.location='<?php echo $app_add_url . $redirect_param; ?>'">
                            Tambah aplikasi baru
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="row">
            <?php if (count($dt_applications)): ?>
                <?php foreach ($dt_applications as $app): ?>
                <div class="col-sm-4">
                    <div class="panel panel-<?php echo ($app['kategori'] == 'Production' ? 'success' : ($app['kategori'] == 'Development' ? 'danger' : 'primary')); ?>">
                        <div class="panel-heading">
                            <p style="display: inline;">
                                <?php echo $app['app_name'] . ' (' . $app['kategori'] . ')'; ?>
                                <a href="javascript:void(0)"
                                    class="btn pull-right" style="margin-left: 6px; background: #fff; padding: 0 4px;"
                                    onclick="if(confirm('<?php echo sprintf(lang('global msg delete_confirm'), $app['app_name']); ?>')) {
                                        $.post('<?php echo $app_delete_url; ?>', {id: '<?php echo $app['id']; ?>', <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'}, function(){
                                            location.reload();
                                        })
                                    }">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                                <a href="javascript:void(0)" onclick="window.location='<?php echo $app_edit_url . $app['id'] . $redirect_param; ?>';"
                                    class="btn pull-right" style="margin-left: 6px; background: #fff; padding: 0 4px;">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </p>
                        </div>
                        <table class="table table-condensed table-hover">
                            <tr>
                                <td class="active" style="width: 1%;">Status</td>
                                <td><?php echo $app['status']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Kategori</td>
                                <td><?php echo $app['app_kategori']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Bahasa</td>
                                <td><?php echo $app['app_bahasa_program']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Jenis</td>
                                <td><?php echo $app['app_jenis']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Url</td>
                                <td><?php echo empty($app['app_url']) ? null : '<a href="' . $app['app_url'] . '">' . $app['app_url'] . '</a>'; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Path</td>
                                <td><?php echo $app['app_path']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Port</td>
                                <td><?php echo $app['app_port']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Service</td>
                                <td><?php echo $app['app_service']; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Repositori</td>
                                <td><?php echo empty($app['git_url']) ? null : '<a href="' . $app['git_url'] . '">' . $app['git_url'] . '</a>'; ?></td>
                            </tr>
                            <tr>
                                <td class="active" style="width: 1%;">Pemeliharaan</td>
                                <td><?php echo $app['status_pemeliharaan']; ?></td>
                            </tr>
                        </table>
                        <div class="panel-body">
                            <?php echo empty($app['keterangan']) ? 'Tidak ada keterangan tambahan.' : $app['keterangan']; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            <?php else: ?>
                <div class="col-sm-12">
                    <p>Belum ada aplikasi yang terdaftar pada server ini.</p>
                </div>
            <?php endif;?>
        </div>
    <?php else: ?>
        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
            <li role="presentation" class="active">
                <a href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <h4 style="font-size: inherit; font-weight: bold; margin-top: 3px; margin-bottom: 3px;">
                        Aplikasi
                    </h4>
                </a>
            </li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <p>Silahkan simpan dan kembali lagi ke form ini untuk menambahkan aplikasi.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($dt_id)): ?>
        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
            <li role="presentation" class="active">
                <a href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <h4 style="font-size: inherit; font-weight: bold; margin-top: 3px; margin-bottom: 3px;">
                        Server Terkait (TODO)
                    </h4>
                </a>
            </li>
        </ul>
    <?php endif; ?>

    <?php // buttons ?>
    <div class="row">
        <div class="col-sm-12">
            <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> <?php echo lang('global button save'); ?></button>
            <a class="btn btn-link" href="<?php echo $cancel_url; ?>"><?php echo lang('global button cancel'); ?></a>
        </div>
    </div>

<?php echo form_close(); ?>
