<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<div class="header">
    <div class="row">
        <div class="col-md-6">
            <h2 style="display: inline;"><?php echo $title; ?></h2>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right">
                <a href="<?php echo $this_url; ?>/add" title="<?php echo lang('global tooltip add'); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('global button add'); ?></a>
                <a href="<?php echo $this_url; ?>/export?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?><?php echo $filter; ?>" title="<?php echo lang('global tooltip csv_export'); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-export"></span> CSV</a>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-condensed table-hover" style="margin-bottom: unset;">
        <thead>

            <?php // sortable headers ?>
            <tr>
                <td>
                    No.
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=nama&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('servers col nama'); ?></a>
                    <?php if ($sort == 'nama'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=os&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('servers col os'); ?></a>
                    <?php if ($sort == 'os'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=processor&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('servers col processor'); ?></a>
                    <?php if ($sort == 'processor'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=memory&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('servers col memory'); ?></a>
                    <?php if ($sort == 'memory'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=storage&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('servers col storage'); ?></a>
                    <?php if ($sort == 'storage'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td class="pull-right">
                    <?php echo lang('global col actions'); ?>
                </td>
            </tr>

            <?php // search filters ?>
            <?php if ($dt_total or !empty($filters)): ?>
                <tr>
                    <?php echo form_open("{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role' => 'form', 'id' => "filters")); ?>
                        <td>
                            &nbsp;
                        </td>
                        <td<?php echo ((isset($filters['nama'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'nama', 'id' => 'nama', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('servers input nama'), 'value' => set_value('nama', ((isset($filters['nama'])) ? $filters['nama'] : '')))); ?>
                        </td>
                        <td<?php echo ((isset($filters['os'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'os', 'id' => 'os', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('servers input processor'), 'value' => set_value('processor', ((isset($filters['processor'])) ? $filters['processor'] : '')))); ?>
                        </td>
                        <td<?php echo ((isset($filters['processor'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'processor', 'id' => 'processor', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('servers input processor'), 'value' => set_value('processor', ((isset($filters['processor'])) ? $filters['processor'] : '')))); ?>
                        </td>
                        <td<?php echo ((isset($filters['memory'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'memory', 'id' => 'memory', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('servers input memory'), 'value' => set_value('memory', ((isset($filters['memory'])) ? $filters['memory'] : '')))); ?>
                        </td>
                        <td<?php echo ((isset($filters['storage'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'storage', 'id' => 'storage', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('servers input storage'), 'value' => set_value('storage', ((isset($filters['storage'])) ? $filters['storage'] : '')))); ?>
                        </td>
                        <td colspan="3">
                            <div class="text-right">
                                <button type="submit" name="submit" value="<?php echo lang('global button filter'); ?>" class="btn btn-default btn-sm tooltips" data-toggle="tooltip" title="<?php echo lang('global tooltip filter'); ?>" style="height: unset; line-height: 1.3; border-radius: 2px;"><span class="glyphicon glyphicon-filter"></span></button>
                                <a href="<?php echo $this_url; ?>" class="btn btn-default btn-sm tooltips" data-toggle="tooltip" title="<?php echo lang('global tooltip filter_reset'); ?>" style="height: unset; line-height: 1.3; border-radius: 2px;"><span class="glyphicon glyphicon-refresh"></span>\</a>
                            </div>
                        </td>
                    <?php echo form_close(); ?>
                </tr>
            <?endif;?>

        </thead>
        <tbody>

            <?php // data rows ?>
            <?php $i = 1;if ($dt_total): ?>
                <?php foreach ($dt_rows as $row): ?>
                    <tr>
                        <td>
                            <?php echo $offset + $i++; ?>
                        </td>
                        <td<?php echo (($sort == 'nama') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['nama']; ?>
                        </td>
                        <td<?php echo (($sort == 'os') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['os']; ?>
                        </td>
                        <td<?php echo (($sort == 'processor') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['processor']; ?>
                        </td>
                        <td<?php echo (($sort == 'memory') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['memory']; ?>
                        </td>
                        <td<?php echo (($sort == 'storage') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['storage']; ?>
                        </td>
                        <td>
                            <div class="text-right">
                                <div class="btn-group">
                                    <a href="javascript:void(0)"
                                        class="btn btn-danger btn-xs"
                                        title="<?php echo lang('global button delete'); ?>"
                                        style="border-radius: 2px;"
                                        onclick="if(confirm('<?php echo sprintf(lang('global msg delete_confirm'), $row['nama']); ?>')) {
                                            $.post('<?php echo $this_url; ?>/delete', {id: '<?php echo $row['id']; ?>', <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'}, function(){
                                                location.reload();
                                            })
                                        }">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                    <a href="<?php echo $this_url; ?>/edit/<?php echo $row['id']; ?>"
                                        class="btn btn-warning btn-xs"
                                        title="<?php echo lang('global button edit'); ?>"
                                        style="border-radius: 2px;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else: ?>
                <tr>
                    <td colspan="7">
                        <?php echo lang('global error no_results'); ?>
                    </td>
                </tr>
            <?php endif;?>

        </tbody>
    </table>

    <?php // list tools ?>
    <div class="panel-footer" style="background-color: unset;">
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <i><?php echo sprintf(lang('global label rows'), ($dt_total > 0 ? $offset + 1 : 0) . ' s/d ' . ($offset + $dt_count), $dt_total); ?></i>
            </div>
            <div class="col-sm-4 col-md-7 text-center">
                <?php echo $pagination; ?>
            </div>
            <div class="col-sm-4 col-md-2 text-right">
                <?php if ($dt_total > 10): ?>
                    <select id="limit" class="form-control" style="height: unset; padding: 3px 3px; border-radius: 2px;">
                        <option value="10"<?php echo ($limit == 10 or ($limit != 10 && $limit != 25 && $limit != 50 && $limit != 75 && $limit != 100)) ? ' selected' : ''; ?>>10 <?php echo lang('global input items_per_page'); ?></option>
                        <option value="25"<?php echo ($limit == 25) ? ' selected' : ''; ?>>25 <?php echo lang('global input items_per_page'); ?></option>
                        <option value="50"<?php echo ($limit == 50) ? ' selected' : ''; ?>>50 <?php echo lang('global input items_per_page'); ?></option>
                        <option value="75"<?php echo ($limit == 75) ? ' selected' : ''; ?>>75 <?php echo lang('global input items_per_page'); ?></option>
                        <option value="100"<?php echo ($limit == 100) ? ' selected' : ''; ?>>100 <?php echo lang('global input items_per_page'); ?></option>
                    </select>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

