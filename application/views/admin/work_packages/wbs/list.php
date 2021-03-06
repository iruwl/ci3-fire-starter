<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<div class="header">
    <div class="row">
        <div class="col-md-6">
            <h2 style="display: inline;"><?php echo $title; ?></h2>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right">
                <a href="<?php echo $this_url; ?>/<?php echo $project_id?>/wbs/new" title="<?php echo lang('global tooltip add'); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('global button add'); ?></a>
                <!-- <a href="<?php echo $this_url; ?>/export?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?><?php echo $filter; ?>" title="<?php echo lang('global tooltip csv_export'); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-export"></span> CSV</a> -->
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-condensed table-hover tree" style="margin-bottom: unset;">
        <thead>

            <?php // sortable headers ?>
            <tr>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=nama&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('wbs col nama'); ?></a>
                    <?php if ($sort == 'nama'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=deskripsi&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('wbs col deskripsi'); ?></a>
                    <?php if ($sort == 'deskripsi'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=priority&dir=<?php echo (($dir == 'asc') ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('wbs col priority'); ?></a>
                    <?php if ($sort == 'priority'): ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif;?>
                </td>
                <td class="pull-right">
                    <?php echo lang('global col actions'); ?>
                </td>
            </tr>

            <?php // search filters ?>
            <?php if ($dt_total or !empty($filters)): ?>
                <tr>
                    <?php echo form_open("{$this_url}/$project_id/wbs?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role' => 'form', 'id' => "filters")); ?>
                        <td<?php echo ((isset($filters['nama'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'nama', 'id' => 'nama', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('wbs input nama'), 'value' => set_value('nama', ((isset($filters['nama'])) ? $filters['nama'] : '')))); ?>
                        </td>
                        <td<?php echo ((isset($filters['deskripsi'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'deskripsi', 'id' => 'deskripsi', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('wbs input deskripsi'), 'value' => set_value('deskripsi', ((isset($filters['deskripsi'])) ? $filters['deskripsi'] : '')))); ?>
                        </td>
                        <td<?php echo ((isset($filters['priority'])) ? ' class="has-success"' : ''); ?>>
                            <?php echo form_input(array('name' => 'priority', 'id' => 'priority', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class' => 'form-control', 'placeholder' => lang('wbs input priority'), 'value' => set_value('priority', ((isset($filters['priority'])) ? $filters['priority'] : '')))); ?>
                        </td>
                        <td colspan="3">
                            <div class="text-right">
                                <button type="submit" name="submit" value="<?php echo lang('global button filter'); ?>" class="btn btn-default btn-sm tooltips" data-toggle="tooltip" title="<?php echo lang('global tooltip filter'); ?>" style="height: unset; line-height: 1.3; border-radius: 2px;"><span class="glyphicon glyphicon-filter"></span></button>
                                <a href='<?php echo "{$this_url}/$project_id/wbs"; ?>' class="btn btn-default btn-sm tooltips" data-toggle="tooltip" title="<?php echo lang('global tooltip filter_reset'); ?>" style="height: unset; line-height: 1.3; border-radius: 2px;"><span class="glyphicon glyphicon-refresh"></span></a>
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
                    <tr class='<?php echo "treegrid-{$row['id']}" . ($row['parent_id'] > 0 ? " treegrid-parent-{$row['parent_id']}" : "");?>'>
                        <td<?php echo (($sort == 'nama') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['nama']; ?>
                        </td>
                        <td<?php echo (($sort == 'deskripsi') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['deskripsi']; ?>
                        </td>
                        <td<?php echo (($sort == 'priority') ? ' class="sorted"' : ''); ?>>
                            <?php echo $row['priority']; ?>
                        </td>
                        <td>
                            <div class="text-right">
                                <a href="<?php echo $this_url; ?>/edit/<?php echo $row['id']; ?>"
                                    class="btn btn-primary btn-xs"
                                    title="<?php echo lang('global button tasks'); ?>"
                                    style="border-radius: 2px;">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </a>
                                <div class="btn-group">
                                    <a href='<?php echo "{$this_url}/$project_id/wbs/edit/{$row['id']}"; ?>'
                                        class="btn btn-warning btn-xs"
                                        title="<?php echo lang('global button edit'); ?>"
                                        style="border-radius: 2px;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="btn btn-danger btn-xs"
                                        title="<?php echo lang('global button delete'); ?>"
                                        style="border-radius: 2px;"
                                        onclick="if(confirm('<?php echo sprintf(lang('global msg delete_confirm'), $row['nama']); ?>')) {
                                            $.post('<?php echo $this_url; ?>/wbs/delete', {id: '<?php echo $row['id']; ?>', <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'}, function(){
                                                location.reload();
                                            })
                                        }">
                                        <span class="glyphicon glyphicon-trash"></span>
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

