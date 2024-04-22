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
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">ИЗМЕНИТЬ РЕЦЕПТ</p>
		<form action="/update/<?= $recipe['ID'] ?>/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" placeholder="Название рецепта"
								   value="<?= $recipe['TITLE'] ?>" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" required
									  name="DESCRIPTION"><?= $recipe['DESCRIPTION'] ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="number" value="<?= $recipe['TIME'] ?>"
								   placeholder="Время приготовления" required>
						</p>
					</div>
				</div>
			</div>
			<div class="product_container">
				<div id="container" class="products_selects">
					<?php foreach ($arResult['USED_PRODUCTS'] as $productSelect): ?>
						<div class="select_container" id="container_<?= $productsCount ?>">
							<div class="select select_div">
								<select name="PRODUCTS[]" id="PRODUCT_<?= $productsCount ?>" class="product_select">
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
								id="PRODUCT_QUANTITY_<?= $productsCount ?>"
								type="text"
								class="input product_input"
								required
								name="PRODUCTS_QUANTITY[]"
								<?= isset($productSelect['VALUE']) ? "value='" . $productSelect['VALUE'] . "'" : '' ?>
							>
							<div class="select select_div" id="select_div_<?= $productsCount ?>">
								<select name="MEASURES[]" id="MEASURE_<?= $productsCount ?>">
									<?php foreach ($arResult['PRODUCT_MEASURES'] as $product): ?>
										<?php foreach ($product as $measure): ?>
											<option <?= ($productSelect['MEASURE_ID'] === $measure['ID']) ? 'selected' : '' ?>
												value="<?= $measure['ID'] ?>"
											>
												<?= $measure['MEASURE_NAME'] ?>
											</option>
										<?php endforeach; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php $productsCount++ ?>
					<?php endforeach; ?>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" onClick="createSelect()">Добавить
						продукт
					</button>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" onClick="deleteSelect()">Удалить
						продукт
					</button>
				</div>
			</div>
			<div id="step_container">
				<?php foreach ($arResult['STEPS'] as $step): ?>
					<textarea class="textarea"
							  name="STEPS[]"
							  id="textarea-<?= $step['STEP'] ?>"
							  placeholder="Описание шага"
							  required
					><?= $step['DESCRIPTION'] ?></textarea>
				<?php endforeach; ?>
			</div>
			<div class="step_btn">
				<button class="button is-primary is-expanded" type="button" onClick="createStep()">Добавить шаг</button>
			</div>
			<div class="step_btn">
				<button class="button is-primary is-expanded" type="button" onClick="deleteStep()">Удалить шаг</button>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<?php
							echo bitrix_sessid_post();
							?>
							<input type="file" name="IMAGES" id="img_inp">
							<img id="img_pre" src="<?= $arResult['IMAGE'] ?>" alt="your image"/>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="button is-primary" id="submit_button">
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
	const products = JSON.parse('<?=$products;?>');
	const measures = JSON.parse('<?=$productMeasures;?>');
	const body = document.getElementById("container");
	const imgInp = document.getElementById("img_inp");
	const imgPre = document.getElementById("img_pre");
	let textareaCount = <?=$stepsSize?>;
	let selectCount = <?=$productsSize?>;
	const stepContainer = document.getElementById("step_container")
	let submit_button = document.getElementById("submit_button");

	let emptyProducts = [];
	let hasNotEmptyProducts = true;

	for (let i = 1; i <= selectCount; i++) {
		let startSelect = document.getElementById(`PRODUCT_${i}`);
		let input = document.getElementById(`PRODUCT_QUANTITY_${i}`);
		let measure_select = document.getElementById(`MEASURE_${i}`);
		let div2 = document.getElementById(`select_div_${i}`);
		measure_select.id = `MEASURE_${i}`;
		measure_select.name = `MEASURES[]`;
		div2.className = `select select_div`;
		div2.id = `select_div_${i}`;

		startSelect.addEventListener('change', function () {
			let selectedValue = this.value;
			let selectedText = this.options[this.selectedIndex].text;
			measure_select.innerHTML = '';
			buttonCheck()
			if (selectedText === 'Выберите продукт') {
				document.getElementById(`PRODUCT_QUANTITY_${i}`).remove();
				emptyProducts[i] = true;
				document.getElementById(`MEASURE_${i}`).remove();
				measure_select.remove();
				document.getElementById(`select_div_${i}`).remove();
				hasNotEmptyProducts = checkArray(emptyProducts);
				buttonCheck()
			} else {
				input.value = ``;
				document.getElementById(`container_${i}`).appendChild(input);
				emptyProducts[i]  = false;
				div2.appendChild(measure_select);
				document.getElementById(`container_${i}`).appendChild(div2);
				hasNotEmptyProducts = checkArray(emptyProducts);
				buttonCheck()
			}
			measures[selectedValue].forEach(function (option) {
				let secondOption = document.createElement('option');
				secondOption.value = option.ID;
				secondOption.text = option.MEASURE_NAME;
				measure_select.appendChild(secondOption);
			});
			hasNotEmptyProducts = checkArray(emptyProducts);
			buttonCheck();
		});
	}

	function checkArray(emptyProducts) {
		for (let i = 0; i < emptyProducts.length; i++) {
			if (emptyProducts[i] === true) {
				return false;
			}
		}
		return true;
	}

	function createSelect() {
		if (selectCount < 15) {
			selectCount++;
			emptyProducts[selectCount] = true;
			hasNotEmptyProducts = checkArray(emptyProducts);
			buttonCheck()
			const select = document.createElement("select");
			const measure_select = document.createElement("select");
			const input = document.createElement("input");
			const div = document.createElement("div");
			const div2 = document.createElement("div");
			const container = document.createElement("div");
			select.id = `PRODUCT_${selectCount}`;
			select.name = `PRODUCTS[]`;
			measure_select.id = `MEASURE_${selectCount}`;
			measure_select.name = `MEASURES[]`;

			input.id = `PRODUCT_QUANTITY_${selectCount}`;
			input.required = true;
			input.name = `PRODUCTS_QUANTITY[]`;

			select.className = `product_select`;
			input.className = `input product_input`;
			container.className = `select_container`
			container.id = `container_${selectCount}`;
			div.className = `select select_div`;
			div2.className = `select select_div`;
			div2.id = `select_div_${selectCount}`;

			div.appendChild(select);

			container.appendChild(div);
			body.appendChild(container);
			let placeholder = document.createElement("option");
			placeholder.text = "Выберите продукт";
			select.appendChild(placeholder);
			products.forEach(function (option) {
				var firstOption = document.createElement('option');
				firstOption.value = option.ID;
				firstOption.text = option.NAME;
				select.appendChild(firstOption);
			});
			for (let i = 1; i <= selectCount; i++) {
				hasNotEmptyProducts = checkArray(emptyProducts);
				buttonCheck()
				select.addEventListener('change', function () {
					var selectedValue = this.value;
					var selectedText = this.options[this.selectedIndex].text;
					measure_select.innerHTML = '';
					if (selectedText === placeholder.text) {
						emptyProducts[i] = true;
						div2.remove();
						input.remove();
						hasNotEmptyProducts = checkArray(emptyProducts);
						buttonCheck()
					} else {
						emptyProducts[i] = false;
						container.appendChild(input);
						div2.appendChild(measure_select);
						container.appendChild(div2);
						hasNotEmptyProducts = checkArray(emptyProducts);
						buttonCheck()
					}
					measures[selectedValue].forEach(function (option) {
						var secondOption = document.createElement('option');
						secondOption.value = option.ID;
						secondOption.text = option.MEASURE_NAME;
						measure_select.appendChild(secondOption);
					});
					hasNotEmptyProducts = checkArray(emptyProducts);
					buttonCheck()
				});
			}
		}
	}

	function deleteSelect() {
		hasNotEmptyProducts = checkArray(emptyProducts);
		buttonCheck()
		const element = document.getElementById(`container_${selectCount}`);
		element.remove();
		selectCount--;
		hasNotEmptyProducts = checkArray(emptyProducts);
		buttonCheck()
	}

	function createStep() {
		if (textareaCount < 10) {
			textareaCount++;
			const textarea = document.createElement('textarea');
			textarea.required = true;
			textarea.name = `STEPS[]`;
			textarea.className = `textarea`;
			textarea.id = `textarea-${textareaCount}`
			stepContainer.appendChild(textarea);
			buttonCheck()
		}
	}

	function deleteStep() {
		const element = document.getElementById(`textarea-${textareaCount}`);
		element.remove();
		textareaCount--;
		buttonCheck()
	}

	imgInp.onchange = evt => {
		const [file] = imgInp.files
		if (file) {
			imgPre.src = URL.createObjectURL(file)
		}
	}

	function buttonCheck() {
		submit_button.disabled = !(textareaCount > 0 && selectCount > 0 && hasNotEmptyProducts === true);
	}

</script>
