<div class="row">
    <label class="col-xl-3"></label>
    <div class="col-lg-9 col-xl-10">
        <h3 class="kt-section__title kt-section__title-sm"><?= lang('Core.info_taxe'); ?>:</h3>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.activation')); ?></label>
    <div class="col-lg-9 col-xl-10">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->active == true) ? 'checked="checked"' : ''; ?> name="active" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<?php if (!empty($form->id)) { ?>
    <div class="form-group row">
        <label class="col-form-label col-3 text-lg-right text-left">Cache</label>
        <div class="col-9">
            <button type="button" class="btn btn-light-primary font-weight-bold btn-sm videCache" data-id-page="<?= $form->id; ?>"><?= lang('Core.Vider le cache'); ?></button>
            <div class="form-text text-muted mt-3">
                <?= lang('Core.Vider la cache de cette page uniquement'); ?>
            </div>
        </div>
    </div>
<?php } ?>

<div class="form-group form-group-sm row">
    <label for="template" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.template')); ?></label>
    <div class="col-lg-9 col-xl-10">
        <select required name="template" class="form-control selectpicker file kt-selectpicker" data-actions-box="true" title="<?= ucfirst(lang('Core.choose_one_of_the_following')); ?>" id="template">
            <option <?= $form->template  == "page_default" ? 'selected' : ''; ?> value="page_default"><?= lang('Core.page par defaut'); ?></option>
            <option <?= $form->template  == "page_full_width" ? 'selected' : ''; ?> value="page_full_width"><?= lang('Core.page full width'); ?></option>
            <option <?= $form->template  == "page_full_width_diapo" ? 'selected' : ''; ?> value="page_full_width_diapo"><?= lang('Core.page full width + diapo'); ?></option>
            <option <?= $form->template  == "page_boxed" ? 'selected' : ''; ?> value="page_boxed"><?= lang('Core.page boxed'); ?></option>
            <option <?= $form->template  == "page_boxed_diapo" ? 'selected' : ''; ?> value="page_boxed_diapo"><?= lang('Core.page boxed + diapo'); ?></option>
            <option <?= $form->template  == "page_actu_boxed" ? 'selected' : ''; ?> value="page_actu_boxed"><?= lang('Core.page actualite'); ?></option>
            <option <?= $form->template  == "page_contact" ? 'selected' : ''; ?> value="page_contact"><?= lang('Core.page contact'); ?></option>
            <option <?= $form->template  == "code" ? 'selected' : ''; ?> value="code"><?= lang('Core.page personnalisée'); ?></option>
            page_contact
        </select>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label for="id_parent" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.parent')); ?></label>
    <div class="col-lg-9 col-xl-10">
        <select name="id_parent" class="form-control selectpicker file kt-selectpicker" data-actions-box="true" title="<?= ucfirst(lang('Core.choose_one_of_the_following')); ?>" id="template">
            <?= generate_menuOption(0, 0, $form->allPages, $form->id_parent); ?>
        </select>
    </div>
</div>


<div class="form-group row kt-shape-bg-color-1">
    <label for="name" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.name')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_input_spread('name', $form->_prepareLang(), 'id="name" class="form-control lang"', 'text', true); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="sous_name" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.sous_name')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_input_spread('name_2', $form->_prepareLang(), 'id="name_2" class="form-control lang"', 'text', false); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="sous_name" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.slug')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_input_spread('slug', $form->_prepareLang(), 'id="slug" class="form-control lang"', 'text', true); ?>
        <span class="form-text text-muted"><?= lang('Core.Voir la page :'); ?> <a target="_blank" href="<?= base_urlFront(getLinkPageAdmin($form, service('settings')->setting_bo_id_lang)); ?>"><?= base_urlFront(getLinkPageAdmin($form, service('settings')->setting_bo_id_lang)); ?></a></span>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="description_short" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description_short')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_textarea_spread('description_short', $form->_prepareLang(), 'class="form-control lang"', false); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="description" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_textarea_spread('description', $form->_prepareLang(), 'id="description" class="form-control lang"', false, 'ckeditor'); ?>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.no_follow_no_index')); ?></label>
    <div class="col-lg-9 col-xl-10">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->no_follow_no_index == true) ? 'checked="checked"' : ''; ?> name="no_follow_no_index" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="meta_title" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.meta_title')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_input_spread('meta_title', $form->_prepareLang(), 'id="meta_title" class="form-control lang"', 'text', false); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="meta_description" class="col-xl-2 col-lg-3 col-form-label"><?= ucfirst(lang('Core.meta_description')); ?>* : </label>
    <div class="col-lg-9 col-xl-10">
        <?= form_input_spread('meta_description', $form->_prepareLang(), 'id="meta_description" class="form-control lang"', 'text', false); ?>
    </div>
</div>

<?php if (!empty($form->id)) { ?> <?= form_hidden('id', $form->id); ?> <?php } ?>
<?php if (!empty($form->handle)) { ?> <?= form_hidden('handle', $form->handle); ?> <?php } ?>