<?php $field = isset($builder->id_field) ? $builder->id_field : "__field__"; ?>
<div class="kt-portlet kt-portlet--solid-grey kt-portlet--height-fluid <?= ($field == '__field__') ? '' : ' kt-portlet--collapse'; ?>" data-ktportlet="true" id="kt_portlet_tools<?= $field; ?>">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                <?= lang('Core.image'); ?> <?= isset($builder->handle) ? ' : ' . $builder->handle : ""; ?>
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-group">
                <a href="javascript:;" data-ktportlet-tool="toggle" data-field="<?= $field; ?>" class="btn btn-sm btn-icon btn-brand btn-icon-md"><i class="la la-angle-down"></i></a>
                <a href="javascript:;" data-ktportlet-tool="remove" data-id_builder="<?= isset($builder->id_builder) ? $builder->id_builder : ""; ?>" data-field="<?= $field; ?>" class="btn btn-sm btn-icon btn-danger removePortlet btn-icon-md"><i class="la la-close"></i></a>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body" <?= ($field == '__field__') ? '' : 'style="display: none;overflow: hidden;padding-top: 0px;padding-bottom: 0px;"'; ?>>
        <div class="kt-portlet__content">
            <div class="row li_row form_output" data-type="image" data-field="<?= $field; ?>">

                <div class="col-md-12">
                    <div>
                        <?php $options = [
                            'acceptedFiles' => '.jpg, .jpeg, .png',
                            'maxFiles' => 1,
                            'uploadMultiple' => false,
                            'crop' => true,
                            'type' => 'image',
                            'field' => $field,
                            'builder' => (isset($builder)) ? $builder : null
                        ]; ?>
                        <?= view('/Admin/Themes/metronic/controllers/medias/bundleUploadCrop', $options) ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="builder[<?= $field; ?>][class]" class="form-control form_input_label" value="<?= isset($builder->class) ? $builder->class : ""; ?>" data-field="<?= $field; ?>" placeholder="Votre class" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="builder[<?= $field; ?>][id]" data-field="<?= $field; ?>" class="form-control form_input_placeholder" value="<?= isset($builder->id) ? $builder->id : ""; ?>" placeholder="Votre id" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="builder[<?= $field; ?>][handle]" data-field="<?= $field; ?>" class="form-control form_input_placeholder" value="<?= isset($builder->handle) ? $builder->handle : ""; ?>" placeholder="Handle" />
                    </div>
                </div>
                <?php if ($field != "__field__") { ?>
                    <?= form_hidden('builder[' . $field . '][id_builder]', $builder->id_builder); ?>
                <?php } ?>
                <?= form_hidden('builder[' . $field . '][type]', 'imagefield'); ?>
                <?= form_hidden('builder[' . $field . '][options]', (isset($builder->options)) ? $builder->options : ''); ?>
                <?= form_hidden('builder[' . $field . '][id_field]', $field); ?>
                <?= form_hidden('builder[' . $field . '][page_id_page]', $form->id_page); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($field == "__field__") { ?>
    __script__
<?php } ?>