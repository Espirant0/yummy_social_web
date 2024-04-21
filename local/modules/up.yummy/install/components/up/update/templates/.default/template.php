<?php
\Bitrix\Main\UI\Extension::load('up.yummy-selector');
\Bitrix\Main\UI\Extension::load('ui.entity-selector');

/**
 * @var $arResult
 */
$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
$productsCount = 1;
$stepsSize = $arResult['STEPS_SIZE'];
$productsSize = $arResult['PRODUCTS_SIZE'];
$recipe = $arResult['RECIPE'];
var_dump($arResult['PRODUCT_MEASURES']);
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
						<div class="select_container" id="container_<?=$productsCount?>">
							<div class="select select_div">
								<select name="PRODUCTS[]" id="PRODUCT_<?=$productsCount?>" class="product_select">
									<option>Выберите продукт</option>
									<?php foreach ($arResult['PRODUCTS'] as $product):?>
										<option <?=($product['ID'] === $productSelect['PRODUCT_ID'])? 'selected':''?>
												value="<?=$product['ID']?>"
										>
											<?=$product['NAME']?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<input
									id="PRODUCT_QUANTITY_<?=$productsCount?>"
									type="text"
									class="input product_input"
									required
									name="PRODUCTS_QUANTITY[]"
									<?=isset($productSelect['VALUE'])? "value='".$productSelect['VALUE']."'":''?>
							>
							<div class="select select_div">
								<select name="MEASURES[]" id="MEASURE_<?=$productsCount?>">
									<?php foreach ($arResult['PRODUCT_MEASURES'] as $product):?>
										<?php foreach ($product as $measure):?>
											<option <?=($productSelect['MEASURE_ID'] === $measure['ID'])? 'selected':''?>
													value="<?=$measure['ID']?>"
											>
												<?=$measure['MEASURE_NAME']?>
											</option>
										<?php endforeach; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php $productsCount++?>
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
                            <img id="img_pre" src="<?=$arResult['IMAGE']?>" alt="your image" />
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
    const imgInp=document.getElementById("img_inp");
    const imgPre=document.getElementById("img_pre");
	var textareaCount = <?=$stepsSize?>;
	var selectCount = <?=$productsSize?>;
	const stepContainer = document.getElementById("step_container")
    let submit_button = document.getElementById("submit_button");

	for(let i = 1; i <= selectCount; i++)
	{
		startSelect = document.getElementById(`PRODUCT_${i}`);
		input = document.getElementById(`PRODUCT_QUANTITY_${i}`);
		measure_select = document.getElementById(`MEASURE_${i}`);
		startSelect.addEventListener('change', function() {
			var selectedValue = this.value;
			var selectedText = this.options[this.selectedIndex].text;
			measure_select.innerHTML = '';
			if(selectedText === 'Выберите продукт')
			{
				document.getElementById(`PRODUCT_QUANTITY_${i}`).remove();
				document.getElementById(`MEASURE_${i}`).remove();
				document.getElementById(`select_div_${i}`).remove();
			}
			else
			{
				container.appendChild(input);
				div2.appendChild(measure_select);
				container.appendChild(div2);
			}
			measures[selectedValue].forEach(function(option) {
				var secondOption = document.createElement('option');
				secondOption.value = option.ID;
				secondOption.text = option.MEASURE_NAME;
				measure_select.appendChild(secondOption);
			});
		});
	}

	function createSelect() {
		selectCount++;
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
		products.forEach(function(option) {
			var firstOption = document.createElement('option');
			firstOption.value = option.ID;
			firstOption.text = option.NAME;
			select.appendChild(firstOption);
		});

		select.addEventListener('change', function() {
			var selectedValue = this.value;
			var selectedText = this.options[this.selectedIndex].text;
			measure_select.innerHTML = '';
			if(selectedText === placeholder.text)
			{
				document.getElementById(`PRODUCT_QUANTITY_${selectCount}`).remove();
				document.getElementById(`MEASURE_${selectCount}`).remove();
				document.getElementById(`select_div_${selectCount}`).remove();
			}
			else
			{
				container.appendChild(input);
				div2.appendChild(measure_select);
				container.appendChild(div2);
			}
			measures[selectedValue].forEach(function(option) {
				var secondOption = document.createElement('option');
				secondOption.value = option.ID;
				secondOption.text = option.MEASURE_NAME;
				measure_select.appendChild(secondOption);
			});
		});
		buttonCheck();
	}

	function deleteSelect() {
		const element = document.getElementById(`container_${selectCount}`);
		element.remove();
		selectCount--;
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
    function buttonCheck()
    {
        submit_button.disabled = !(textareaCount > 0 && productsCount > 0);
    }

</script>
