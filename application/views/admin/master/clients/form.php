<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<div class="header">
    <h2 style="display: inline;"><?php echo $title; ?></h2>
</div>

<button type="button" id="klik" class="btn btn-primary">Klik</button>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php echo form_open('', array('role' => 'form')); ?>

    <?php // hidden id ?>
    <?php if (isset($dt_id)): ?>
        <?php echo form_hidden('id', $dt_id); ?>
    <?php endif;?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo form_error('nama') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('clients input nama'), 'nama', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'nama', 'value' => set_value('nama', (isset($dt['nama']) ? $dt['nama'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('alamat') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('clients input alamat'), 'alamat', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'alamat', 'value' => set_value('alamat', (isset($dt['alamat']) ? $dt['alamat'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('kategori') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('clients input kategori'), 'kategori', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('kategori', $dt_kategori, (isset($dt['kategori']) ? $dt['kategori'] : 'Pemerintah'), 'id="kategori" class="form-control select2"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php echo form_error('status') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('clients input status'), 'status', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_dropdown('status', $dt_status, (isset($dt['status']) ? $dt['status'] : 'NA'), 'id="status" class="form-control select2"'); ?>
                    </div>
                </div>
            </div>
            <div class="form-group <?php echo form_error('keterangan') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('clients input keterangan'), 'keterangan', array('class' => 'control-label')); ?>
                <?php echo form_input(array('name' => 'keterangan', 'value' => set_value('keterangan', (isset($dt['keterangan']) ? $dt['keterangan'] : '')), 'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Kontak</h3>
                </div>
                <?php
                    // template
                    echo form_dropdown("select_contacts", $dt_contacts['ref'], null, 'class="form-control" style="display: none;"');
                ?>
                <table class="table" id="contact_fields">
                    <tr>
                        <th style="vertical-align: middle;">Nama</th>
                        <!--<th style="vertical-align: middle;">Email</th>-->
                        <th style="vertical-align: middle; width: 150px;">HP</th>
                        <th style="vertical-align: middle; width: 50px;">
                            <button type="button" class="btn btn-default btn-sm" id="add_contact" tabindex="-1"><span class="glyphicon glyphicon-plus"></span></button>
                        </th>
                    </tr>
                    <?php foreach ($dt_contacts['data'] as $index => $row): ?>
                        <tr class="contact-row" id="<?php echo $index; ?>">
                            <td>
                                <?php echo form_dropdown("contacts[$index]", $dt_contacts['ref'], (isset($row['id']) ? $row['id'] : null), 'class="form-control select2"'); ?>
                            </td>
                            <!--<td><?php //echo form_input(array('value' => (isset($row['email']) ? $row['email'] : null), 'class' => 'form-control', 'readonly' => 'readonly')); ?></td>-->
                            <td><?php echo form_input(array('value' => (isset($row['hp1']) ? $row['hp1'] : null), 'class' => 'form-control', 'readonly' => 'readonly')); ?></td>
                            <td><button type="button" class="btn btn-default btn-sm btn_remove_contact" id="<?php echo $index; ?>" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>
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
