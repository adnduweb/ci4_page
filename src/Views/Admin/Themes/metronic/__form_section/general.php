<div class="row">
    <label class="col-xl-3"></label>
    <div class="col-lg-9 col-xl-6">
        <h3 class="kt-section__title kt-section__title-sm"><?= lang('Core.info_taxe'); ?>:</h3>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.activation')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->active == true) ? 'checked="checked"' : ''; ?> name="active" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label for="template" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.template')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <select required name="template" class="form-control selectpicker file kt-selectpicker" data-actions-box="true" title="<?= ucfirst(lang('Core.choose_one_of_the_following')); ?>" id="template">
            <option <?= $form->template  == "default" ? 'selected' : ''; ?> value="default">Standard</option>
            <option <?= $form->template  == "code" ? 'selected' : ''; ?> value="code">Code</option>
        </select>
    </div>
</div>


<div class="form-group row kt-shape-bg-color-1">
    <label for="name" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.name')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_input_spread('name', $form->_prepareLang(), 'id="name" class="form-control lang"', 'text', true); ?>
    </div>
</div>
<div class="form-group row">
    <label for="slug" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.slug')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <input class="form-control" required type="text" value="<?= old('slug') ? old('slug') : $form->slug; ?>" name="slug" id="slug">
        <span class="form-text text-muted"><?= lang('Core.Voir la page :'); ?> <a target="_blank" href="<?= base_urlFront($form->slug); ?>"><?= base_urlFront($form->slug); ?></a></span>
        <div class="invalid-feedback"><?= lang('Core.this_field_is_requis'); ?> </div>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="description_short" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description_short')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_textarea_spread('description_short', $form->_prepareLang(), 'class="form-control lang"', true); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="description" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_textarea_spread('description', $form->_prepareLang(), 'id="description" class="form-control lang"', true, 'ckeditor'); ?>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.no_follow_no_index')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->no_follow_no_index == true) ? 'checked="checked"' : ''; ?> name="no_follow_no_index" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="meta_title" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.meta_title')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_input_spread('meta_title', $form->_prepareLang(), 'id="meta_title" class="form-control lang"', 'text', true); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="meta_description" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.meta_description')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_input_spread('meta_description', $form->_prepareLang(), 'id="meta_description" class="form-control lang"', 'text', true); ?>
    </div>
</div>

<?php if (!empty($form->id_page)) { ?> <?= form_hidden('id_page', $form->id_page); ?> <?php } ?>