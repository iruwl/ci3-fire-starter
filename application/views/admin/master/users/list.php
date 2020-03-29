<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="header">
    <div class="row">
        <div class="col-md-6">
            <h2 style="display: inline;"><?php echo $title; ?></h2>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right">
                <a href="<?php echo $this_url; ?>/add" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('users button add_new_user'); ?></a>
                <a href="<?php echo $this_url; ?>/export?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?><?php echo $filter; ?>" class="btn btn-primary"><span class="glyphicon glyphicon-export"></span> CSV</a>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-condensed table-striped table-hover" style="margin-bottom: unset;">
        <thead>

            <?php // sortable headers ?>
            <tr>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=id&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('users col user_id'); ?></a>
                    <?php if ($sort == 'id') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=username&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('users col username'); ?></a>
                    <?php if ($sort == 'username') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('users col name'); ?></a>
                    <?php if ($sort == 'name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=status&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('admin col status'); ?></a>
                    <?php if ($sort == 'status') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=is_admin&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('users col is_admin'); ?></a>
                    <?php if ($sort == 'is_admin') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
                <td class="pull-right">
                    <?php echo lang('admin col actions'); ?>
                </td>
            </tr>

            <?php // search filters ?>
            <tr>
                <?php echo form_open("{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
                    <td>
                        &nbsp;
                    </td>
                    <td<?php echo ((isset($filters['username'])) ? ' class="has-success"' : ''); ?>>
                        <?php echo form_input(array('name'=>'username', 'id'=>'username', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class'=>'form-control', 'placeholder'=>lang('users input username'), 'value'=>set_value('username', ((isset($filters['username'])) ? $filters['username'] : '')))); ?>
                    </td>
                    <td<?php echo ((isset($filters['name'])) ? ' class="has-success"' : ''); ?>>
                        <?php echo form_input(array('name'=>'name', 'id'=>'name', 'style' => 'height: unset; padding: 3px 3px; border-radius: 2px;', 'class'=>'form-control', 'placeholder'=>lang('users input name'), 'value'=>set_value('name', ((isset($filters['name'])) ? $filters['name'] : '')))); ?>
                    </td>
                    <td colspan="3">
                        <div class="text-right">
                            <a href="<?php echo $this_url; ?>" class="btn btn-danger btn-sm tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip filter_reset'); ?>" style="height: unset; line-height: 1.3; border-radius: 2px;"><span class="glyphicon glyphicon-refresh"></span> <?php echo lang('core button reset'); ?></a>
                            <button type="submit" name="submit" value="<?php echo lang('core button filter'); ?>" class="btn btn-success btn-sm tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip filter'); ?>" style="height: unset; line-height: 1.3; border-radius: 2px;"><span class="glyphicon glyphicon-filter"></span> <?php echo lang('core button filter'); ?></button>
                        </div>
                    </td>
                <?php echo form_close(); ?>
            </tr>

        </thead>
        <tbody>

            <?php // data rows ?>
            <?php if ($total) : ?>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td<?php echo (($sort == 'id') ? ' class="sorted"' : ''); ?>>
                            <?php echo $user['id']; ?>
                        </td>
                        <td<?php echo (($sort == 'username') ? ' class="sorted"' : ''); ?>>
                            <?php echo $user['username']; ?>
                        </td>
                        <td<?php echo (($sort == 'name') ? ' class="sorted"' : ''); ?>>
                            <?php echo $user['name']; ?>
                        </td>
                        <td<?php echo (($sort == 'status') ? ' class="sorted"' : ''); ?>>
                            <?php echo ($user['status']) ? '<span class="active">' . lang('admin input active') . '</span>' : '<span class="inactive">' . lang('admin input inactive') . '</span>'; ?>
                        </td>
                        <td<?php echo (($sort == 'is_admin') ? ' class="sorted"' : ''); ?>>
                            <?php echo ($user['is_admin']) ? lang('core text yes') : lang('core text no'); ?>
                        </td>
                        <td>
                            <div class="text-right">
                                <div class="btn-group">
                                    <?php if ($user['id'] > 1) : ?>
                                        <a href="javascript:void(0)"
                                            class="btn btn-danger btn-xs"
                                            title="<?php echo lang('admin button delete'); ?>"
                                            style="border-radius: 2px;"
                                            onclick="if(confirm('Hapus data ini (<?php echo lang('users col name'); ?>: <?php echo $user['name']; ?>)?')) {window.location.href='<?php echo $this_url; ?>/delete/<?php echo $user['id']; ?>'}">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo $this_url; ?>/edit/<?php echo $user['id']; ?>"
                                        class="btn btn-warning btn-xs"
                                        title="<?php echo lang('admin button edit'); ?>"
                                        style="border-radius: 2px;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">
                        <?php echo lang('core error no_results'); ?>
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <?php // list tools ?>
    <div class="panel-footer" style="background-color: unset;">
        <div class="row">
            <div class="col-sm-4 col-md-2">
                <i><?php echo sprintf(lang('admin label rows'), $total); ?></i>
            </div>
            <div class="col-sm-4 col-md-8 text-center">
                <?php echo $pagination; ?>
            </div>
            <div class="col-sm-4 col-md-2 text-right">
                <?php if ($total > 10): ?>
                    <select id="limit" class="form-control" style="height: unset; padding: 3px 3px; border-radius: 2px;">
                        <option value="10"<?php echo ($limit == 10 or ($limit != 10 && $limit != 25 && $limit != 50 && $limit != 75 && $limit != 100)) ? ' selected' : ''; ?>>10 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="25"<?php echo ($limit == 25) ? ' selected' : ''; ?>>25 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="50"<?php echo ($limit == 50) ? ' selected' : ''; ?>>50 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="75"<?php echo ($limit == 75) ? ' selected' : ''; ?>>75 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="100"<?php echo ($limit == 100) ? ' selected' : ''; ?>>100 <?php echo lang('admin input items_per_page'); ?></option>
                    </select>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

