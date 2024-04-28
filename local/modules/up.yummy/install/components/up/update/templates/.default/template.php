<?php

/**
 * @var $arResult
 */

$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
$productsCount = 1;
$stepsSize = $arResult['STEPS_SIZE'];
$productsSize = $arResult['PRODUCTS_SIZE'];
$recipe = $arResult['RECIPE'];
?>
<form action="/detail/<?= $recipe['ID'] ?>/" method="get" class="create_btn">
	<button class="button is-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">ИЗМЕНИТЬ РЕЦЕПТ</p>
		<form action="/update/<?= $recipe['ID'] ?>/" method="post" enctype="multipart/form-data" id="form">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" placeholder="Название рецепта"
								   value="<?= $recipe['TITLE'] ?>" id="update_title_input" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" required
									  name="DESCRIPTION" maxlength="250" id="update_description_input"><?= $recipe['DESCRIPTION'] ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="number" min="1" value="<?= $recipe['TIME'] ?>"
								   placeholder="Время приготовления" id="update_time_input" required>
						</p>
					</div>
				</div>
			</div>
			<div class="product_container">
				<div id="container" class="products_selects">
					<?php foreach ($arResult['USED_PRODUCTS'] as $productSelect): ?>
						<div class="select_container" id="container_<?= $productsCount ?>">
							<div class="select select_div">
								<select name="PRODUCTS[]" id="update_product_<?= $productsCount ?>" class="product_select">
									<option>Выберите продукт</option>
									<?php foreach ($arResult['PRODUCTS'] as $product): ?>
										<option <?= ($product['ID'] === $productSelect['PRODUCT_ID']) ? 'selected' : '' ?>
											value="<?= $product['ID'] ?>"
										>
											<?= $product['NAME'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<input
								id="update_product_quantity_<?= $productsCount ?>"
								type="number"
								min="1"
								class="input product_input"
								required
								name="PRODUCTS_QUANTITY[]"
								<?= isset($productSelect['VALUE']) ? "value='" . $productSelect['VALUE'] . "'" : '' ?>
							>
							<div class="select select_div" id="select_div_<?= $productsCount ?>">
								<select name="MEASURES[]" id="update_measure_<?= $productsCount ?>">
									<?php foreach ($arResult['PRODUCT_MEASURES'][$productSelect['PRODUCT_ID']] as $product): ?>
											<option <?= ($productSelect['MEASURE_ID'] === $product['ID']) ? 'selected' : '' ?>
												value="<?= $product['ID'] ?>"
											>
												<?= $product['MEASURE_NAME'] ?>
											</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php $productsCount++ ?>
					<?php endforeach; ?>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" id="add_product_btn" type="button"
							onclick="updateRecipe.createSelect()"
					>Добавить продукт
					</button>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" id="remove_product_btn" type="button" onclick="updateRecipe.deleteSelect()">Удалить
						продукт
					</button>
				</div>
			</div>
			<div id="step_container">
				<?php foreach ($arResult['STEPS'] as $step): ?>
					<textarea class="textarea"
							  name="STEPS[]"
							  id="update_step_description_<?= $step['STEP'] ?>"
							  placeholder="Описание шага"
							  required
							  maxlength="150"
					><?= $step['DESCRIPTION'] ?></textarea>
				<?php endforeach; ?>
			</div>
            <button class="button is-primary" id="delete_photo" type="button">
                Удалить фотографию
            </button>
            <input type="hidden" value="0" name="photoStatus" id="photoStatus">
			<div class="step_btn">
				<button class="button is-primary is-expanded" id="add_step_btn" type="button" onclick="updateRecipe.createStep()">Добавить шаг</button>
			</div>
			<div class="step_btn">
				<button class="button is-primary is-expanded" id="remove_step_btn" type="button" onclick="updateRecipe.deleteStep()">Удалить шаг</button>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<?php
							echo bitrix_sessid_post();
							?>
                            <input type="file" name="IMAGES" id="img_input" accept="image/*">
							<img id="img_pre" src="<?= ($arResult['IMAGE'])??'#' ?>" alt=""/>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="button is-primary" id="update_recipe_btn" type="button">
								Изменить рецепт
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>
<script>
	const updateRecipe = new UpdateRecipe(<?=$products?> , <?=$productMeasures?>, <?=$stepsSize?>, <?=$productsSize?>);
	updateRecipe.init();
</script>
