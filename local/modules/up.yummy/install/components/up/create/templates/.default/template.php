<?php
/**
 * @var array $arParams ;
 * @var array $arResult
 */
$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
?>
<form action="/" method="get" class="create_btn">
	<button class="button is-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">Добавить рецепт</p>
		<form action="/add/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" id="create_title_input" placeholder="Название рецепта" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" name="DESCRIPTION" id="create_description_input" placeholder="Описание рецепта" maxlength="250"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="text" id="create_time_input" pattern="[0-9]{,3}"
								   placeholder="Время приготовления" required>
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
                <button class="button is-primary" id="delete_photo" type="button" disabled>
                    Удалить фотографию
                </button>
			</div>
			<div class="product_container">
				<div id="container" class="products_selects">

				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" id="add_product_btn" onclick="createRecipe.createSelect()">Добавить
						продукт
					</button>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" id="remove_product_btn" onclick="createRecipe.deleteSelect()">Удалить
						продукт
					</button>
				</div>
			</div>
			<div id="step_container">
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" id="add_step_btn"  onclick="createRecipe.createStep()">Добавить шаг
					</button>
				</div>
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" id="remove_step_btn" onclick="createRecipe.deleteStep()">Удалить шаг
					</button>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button type="submit" class="button is-primary" id="create_recipe_btn" disabled>
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
