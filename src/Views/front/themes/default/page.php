<?= $this->extend('/front/themes/default/__layouts/layout') ?>
<?= $this->section('main') ?>
<section class="page_<?= $page->id_page; ?>">
	<div class="adw_container">
		<?= $page->getDescription($id_lang); ?>
	</div>
</section>
<?= $this->endSection() ?>