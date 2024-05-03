<?php
/**
 * @var array $arParams ;
 * @var array $arResult
 */

\Bitrix\Main\UI\Extension::load('up.editForm');
\Bitrix\Main\UI\Extension::load("ui.forms");

$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
?>
<form action="/" method="get" class="create_btn">
	<button class="ui-btn ui-btn-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<p class="title has-text-centered">Добавить рецепт</p>
	<div class="column add_form">
		<form action="/add/" method="post" enctype="multipart/form-data" id="form">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p>Название рецепта</p>
						<p class="ui-ctl ui-ctl-textbox main_input">
							<input class="ui-ctl-element" name="NAME" type="text" id="title_input" maxlength="50" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p>Описание рецепта</p>
						<div class="ui-ctl ui-ctl-textarea ui-ctl-no-resize">
							<textarea class="ui-ctl-element" name="DESCRIPTION" id="description_input" maxlength="250" required></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p>Время приготовления (в минутах)</p>
						<p class="ui-ctl ui-ctl-textbox main_input">
							<input class="ui-ctl-element" name="TIME" type="number" id="time_input" pattern="[0-9]{,3}"
								   min="1" required >
						</p>
					</div>
				</div>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<img id="img_prev" src="#" alt=""/>
						<p class="control">
							<?php echo bitrix_sessid_post(); ?>
                            <input type="file" name="IMAGES" id="img_input" accept="image/*">
						</p>
					</div>
				</div>
			</div>
			<button class="ui-btn ui-btn-success" id="delete_photo" type="button" disabled>
				Удалить фотографию
			</button>
			<div class="product_container">
				<p>Продукты</p>
				<div id="container" class="products_selects">
				</div>
				<div class="buttons">
					<div class="product_btn">
						<button class="ui-btn ui-btn-success ui-btn-icon-add" type="button" id="add_product_btn" onclick="YummyEditForm.createSelect()">Добавить
							продукт
						</button>
					</div>
					<div class="product_btn">
						<button class="ui-btn ui-btn-danger ui-btn-icon-remove" type="button" id="remove_product_btn" onclick="YummyEditForm.deleteSelect()">Удалить
							продукт
						</button>
					</div>
				</div>
			</div>
			<p>Пошаговая инструкция</p>
			<div id="step_container">
			</div>
			<div class="buttons">
				<div class="step_btn">
					<button class="ui-btn ui-btn-success ui-btn-icon-add" type="button" id="add_step_btn"  onclick="YummyEditForm.createStep()">Добавить шаг
					</button>
				</div>
				<div class="step_btn">
					<button class="ui-btn ui-btn-danger ui-btn-icon-remove" type="button" id="remove_step_btn" onclick="YummyEditForm.deleteStep()">Удалить шаг
					</button>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="ui-btn ui-btn-success" type="button" id="confirm_recipe_btn">
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
	BX.ready(function (){
		window.YummyEditForm = new BX.Up.Yummy.EditForm({
			products: <?=$products?>,
			measures: <?=$productMeasures?>,
			stepsSize: 0,
			productsSize: 0
		});
		YummyEditForm.initCreate();
	});
</script>
