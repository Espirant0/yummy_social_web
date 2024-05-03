<?php
/**
 * @var array $arParams ;
 * @var array $arResult
 */
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
						<p class="ui-ctl ui-ctl-textbox main_input">
							<input class="ui-ctl-element" name="NAME" type="text" id="create_title_input" placeholder="Название рецепта" maxlength="50" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="ui-ctl ui-ctl-textarea">
							<textarea class="ui-ctl-element" name="DESCRIPTION" id="create_description_input" placeholder="Описание рецепта" maxlength="250" required></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="ui-ctl ui-ctl-textbox main_input">
							<input class="ui-ctl-element" name="TIME" type="number" id="create_time_input" pattern="[0-9]{,3}"
								   placeholder="Время приготовления" min="1" required >
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
                            <input type="file" name="IMAGES" id="img_input" accept="image/*">
                            <img id="img_pre" src="#" alt=""/>
						</p>
					</div>
				</div>
			</div>
			<button class="ui-btn ui-btn-success" id="delete_photo" type="button" disabled>
				Удалить фотографию
			</button>
			<div class="product_container">
				<div id="container" class="products_selects">
				</div>
				<div class="buttons">
					<div class="product_btn">
						<button class="ui-btn ui-btn-success ui-btn-icon-add" type="button" id="add_product_btn" onclick="createRecipe.createSelect()">Добавить
							продукт
						</button>
					</div>
					<div class="product_btn">
						<button class="ui-btn ui-btn-danger ui-btn-icon-remove" type="button" id="remove_product_btn" onclick="createRecipe.deleteSelect()">Удалить
							продукт
						</button>
					</div>
				</div>
			</div>
			<div id="step_container">
			</div>
			<div class="buttons">
				<div class="step_btn">
					<button class="ui-btn ui-btn-success ui-btn-icon-add" type="button" id="add_step_btn"  onclick="createRecipe.createStep()">Добавить шаг
					</button>
				</div>
				<div class="step_btn">
					<button class="ui-btn ui-btn-danger ui-btn-icon-remove" type="button" id="remove_step_btn" onclick="createRecipe.deleteStep()">Удалить шаг
					</button>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="ui-btn ui-btn-success" type="button" id="create_recipe_btn">
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
	const createRecipe = new CreateRecipe(<?=$products?> , <?=$productMeasures?>);
	createRecipe.init();
</script>
