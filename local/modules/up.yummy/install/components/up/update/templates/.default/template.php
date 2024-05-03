<?php
/**
 * @var $arResult
 */

\Bitrix\Main\UI\Extension::load('up.editForm');
\Bitrix\Main\UI\Extension::load("ui.forms");

$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
$productsCount = 1;
$stepsSize = $arResult['STEPS_SIZE'];
$productsSize = $arResult['PRODUCTS_SIZE'];
$recipe = $arResult['RECIPE'];
?>
<form action="/" method="get" class="create_btn">
	<button class="ui-btn ui-btn-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<p class="title has-text-centered">Изменить рецепт</p>
	<div class="column add_form">
		<form action="/update/<?= $recipe['ID'] ?>/" method="post" enctype="multipart/form-data" id="form">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p>Название рецепта</p>
						<p class="ui-ctl ui-ctl-textbox main_input">
							<input class="ui-ctl-element" name="NAME" type="text"
								   value="<?= $recipe['TITLE'] ?>" id="title_input" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p>Описание рецепта</p>
						<div class="ui-ctl ui-ctl-textarea ui-ctl-no-resize">
							<textarea class="ui-ctl-element" required
									  name="DESCRIPTION" maxlength="250" id="description_input"><?= $recipe['DESCRIPTION'] ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p>Время приготовления (в минутах)</p>
						<p class="ui-ctl ui-ctl-textbox main_input">
							<input class="ui-ctl-element" name="TIME" type="number" min="1" value="<?= $recipe['TIME'] ?>"
								 id="time_input" required>
						</p>
					</div>
				</div>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<img id="img_prev" src="<?= ($arResult['IMAGE'])??'#' ?>" alt=""/>
						<p class="control">
							<?php echo bitrix_sessid_post(); ?>
							<input type="file" name="IMAGES" id="img_input" accept="image/*">
						</p>
					</div>
				</div>
			</div>
			<input type="hidden" value="0" name="photoStatus" id="photo_status">
			<button class="ui-btn ui-btn-success" id="delete_photo" type="button" disabled>
				Удалить фотографию
			</button>
			<div class="product_container">
				<p>Продукты</p>
				<div id="container" class="products_selects">
					<?php foreach ($arResult['USED_PRODUCTS'] as $productSelect): ?>
						<div class="select_container" id="container_<?= $productsCount ?>">
							<div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown select_div">
								<div class="ui-ctl-after ui-ctl-icon-angle"></div>
								<select name="PRODUCTS[]" id="product_<?= $productsCount ?>" class="ui-ctl-element product_select">
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
							<div class="ui-ctl ui-ctl-textbox product_input" id="product_quantity_<?= $productsCount ?>">
								<input
									type="number"
									min="1"
									class="input product_input"
									id="product_quantity_input_<?= $productsCount ?>"
									required
									name="PRODUCTS_QUANTITY[]"
									<?= isset($productSelect['VALUE']) ? "value='" . $productSelect['VALUE'] . "'" : '' ?>
								>
							</div>
							<div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown measure_select_div" id="select_div_<?= $productsCount ?>">
								<div class="ui-ctl-after ui-ctl-icon-angle"></div>
								<select name="MEASURES[]" class="ui-ctl-element measure_angle" id="measure_<?= $productsCount ?>">
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
				<?php foreach ($arResult['STEPS'] as $step): ?>
				<p class="title is-5" id="step_<?= $step['STEP'] ?>">Шаг <?= $step['STEP'] ?></p>
				<div class="ui-ctl-textarea ui-ctl-no-resize step_div" id="step_description_<?= $step['STEP'] ?>">
					<textarea class="ui-ctl-element"
							  name="STEPS[]"
							  placeholder="Описание шага"
							  required
							  id="step_textarea_<?= $step['STEP'] ?>"
							  maxlength="150"
					><?= $step['DESCRIPTION'] ?></textarea>
				</div>
				<?php endforeach; ?>
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
							<button class="ui-btn ui-btn-success" id="confirm_recipe_btn" type="button">
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
	BX.ready(function (){
		window.YummyEditForm = new BX.Up.Yummy.EditForm({
			products: <?=$products?>,
			measures: <?=$productMeasures?>,
			stepsSize: <?=$stepsSize?>,
			productsSize: <?=$productsSize?>
		});
		YummyEditForm.initUpdate();
	});
</script>
