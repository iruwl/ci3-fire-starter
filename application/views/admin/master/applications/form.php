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
                <?php echo form_label(lang('applications input nama'), 'nama', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'nama', 'value' => set_value('nama', (isset($dt['nama']) ? $dt['nama'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('deskripsi') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('applications input deskripsi'), 'deskripsi', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'deskripsi', 'value' => set_value('deskripsi', (isset($dt['deskripsi']) ? $dt['deskripsi'] : '')), 'class' => 'form-control')); ?>
            </div>
            <div class="form-group <?php echo form_error('kategori') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('applications input kategori'), 'kategori', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_dropdown('kategori', $dt_kategori, (isset($dt['kategori']) ? $dt['kategori'] : 'Undefined'), 'id="kategori" class="form-control select2"'); ?>
            </div>
            <div class="form-group <?php echo form_error('jenis') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('applications input jenis'), 'jenis', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_dropdown('jenis', $dt_jenis, (isset($dt['jenis']) ? $dt['jenis'] : 'Undefined'), 'id="jenis" class="form-control select2"'); ?>
            </div>
            <div class="form-group <?php echo form_error('bahasa_program') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('applications input bahasa_program'), 'bahasa_program', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_dropdown('bahasa_program', $dt_bahasa_program, (isset($dt['bahasa_program']) ? $dt['bahasa_program'] : 'Undefined'), 'id="bahasa_program" class="form-control select2"'); ?>
            </div>
            <div class="form-group <?php echo form_error('keterangan') ? ' has-error' : ''; ?>">
                <?php echo form_label(lang('applications input keterangan'), 'keterangan', array('class' => 'control-label')); ?>
                <span class="required">*</span>
                <?php echo form_input(array('name' => 'keterangan', 'value' => set_value('keterangan', (isset($dt['keterangan']) ? $dt['keterangan'] : '')), 'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Dokumen</h3>
                </div>
                <table class="table" id="dokumen_fields">
                    <tr>
                        <!--<th style="width: 120px;">Tanggal</th>-->
                        <th style="vertical-align: middle;">File Name</th>
                        <!--<th style="vertical-align: middle;">File Path</th>-->
                        <th style="vertical-align: middle;">Keterangan</th>
                        <th style="vertical-align: middle; width: 50px;"><button type="button" class="btn btn-default btn-sm" id="add_dokumen" tabindex="-1"><span class="glyphicon glyphicon-plus"></span></button></th>
                    </tr>
                    <?php foreach ($dt_dokumen as $index => $row): ?>
                        <tr class="dokumen-row" id="<?php echo $index; ?>">
                            <td>
                                <div class="input-group">
                                    <?php echo form_input(array('name' => "dokumen[$index][filename]", 'value' => $row['filename'], 'class' => 'form-control', 'readonly' => 'true', 'style' => 'background-color: transparent;')); ?>
                                    <?php echo form_input(array('name' => "dokumen[$index][tanggal]", 'value' => isset($row['tanggal']) ? $row['tanggal'] : date('d-m-Y') , 'class' => 'form-control', 'style' => 'display: none;')); ?>
                                    <?php echo form_input(array('name' => "dokumen[$index][filepath]", 'value' => $row['filepath'], 'class' => 'form-control', 'style'=>'display: none;')); ?>
                                    <span class="input-group-btn">
                                        <label class="btn btn-default"><span class="glyphicon glyphicon-folder-open"></span>
                                            <!--<input type="file" style="display: none;" onchange="$('input[name^=\'dokumen[0][filename]\']').val(this.files[0].name)"> -->
                                            <?php echo form_upload(array('onchange' => "$('input[name^=\'dokumen[$index][filename]\']').val(this.files[0].name); upload(this, $('input[name^=\'dokumen[$index][filepath]\']'));", 'style' => 'display: none;', 'name' => "file")); ?>
                                        </label>
                                    </span>
                                </div>
                            </td>
                            <td><?php echo form_input(array('name' => "dokumen[$index][keterangan]", 'value' => $row['keterangan'], 'class' => 'form-control')); ?></td>
                            <td><button type="button" class="btn btn-default btn-sm btn_remove_dokumen" id="<?php echo $index; ?>" tabindex="-1"><span class="glyphicon glyphicon-minus"></span></button></td>
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
