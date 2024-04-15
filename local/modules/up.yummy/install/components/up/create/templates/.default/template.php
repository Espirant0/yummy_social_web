<?php
\Bitrix\Main\UI\Extension::load('up.yummy-selector');
\Bitrix\Main\UI\Extension::load('ui.entity-selector');
?>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">Добавить рецепт</p>
		<form action="/add/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" placeholder="Название рецепта" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" name="DESCRIPTION" placeholder="Описание рецепта"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="text" pattern="[0-9]{,3}" placeholder="Время приготовления" required>
						</p>
					</div>
				</div>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<?php echo bitrix_sessid_post(); ?>
							<input type="file" name="IMAGES">
						</p>
					</div>
				</div>
			</div>
			<input type="hidden" name="test1" value=""/>
			<div id="test1" name="test1"></div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="button is-primary">
								Добавить рецепт
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	(function() { const tagSelector = new BX.UI.EntitySelector.TagSelector({
		id: 'test1',
		multiple: false,
		dialogOptions: {
			entities: [
				{
					id: 'products',
					dynamicLoad: true,
				},
			],
		}
	});
		tagSelector.renderTo(document.getElementById('test1'))})();
</script>
