 <div class="form_builder" style="margin-top: 25px">
     <div class="row">
         <div class="col-sm-2">
             <nav class="nav-sidebar">
                 <ul class="nav">
                     <li data-compoment="textfield" class="form_bal_textfield">
                         <a href="javascript:;"><i class="fa fa-plus-circle"></i> Titre</a>
                         <div class="compoments compoments_textfield" style="display: none">
                             <?= $this->include('\Spreadaurora\ci4_page\Views\Admin\Themes\metronic\compoments\textfield') ?>
                         </div>
                     </li>
                     <li data-compoment="textarea" class=" form_bal_textarea">
                         <a href="javascript:;"><i class="fa fa-plus-circle"></i> Texte </a>
                         <div class="compoments compoments_textarea" style="display: none">
                             <?= $this->include('\Spreadaurora\ci4_page\Views\Admin\Themes\metronic\compoments\textarea') ?>
                         </div>
                     </li>
                     <li data-compoment="imagefield" class="form_bal_imagefield">
                         <a href="javascript:;"><i class="fa fa-plus-circle"></i> Image </a>
                         <div class="compoments compoments_imagefield" style="display: none">
                             <?= $this->include('\Spreadaurora\ci4_page\Views\Admin\Themes\metronic\compoments\imagefield') ?>
                         </div>
                     </li>
                 </ul>
             </nav>
         </div>
         <div class="col-md-10 bal_builder">
             <div class="form_builder_area">
                 <?php if (isset($form->builders) && !empty($form->builders)) { ?>
                     <?php foreach ($form->builders as $builder) { ?>
                         <?= view('\Spreadaurora\ci4_page\Views\Admin\Themes\metronic\compoments\\' . $builder->type, ['builder' => $builder]) ?>
                     <?php } ?>
                 <?php } ?>

             </div>
         </div>
     </div>
 </div>