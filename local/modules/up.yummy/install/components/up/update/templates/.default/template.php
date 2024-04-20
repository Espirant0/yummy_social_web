<?php
\Bitrix\Main\UI\Extension::load('up.yummy-selector');
\Bitrix\Main\UI\Extension::load('ui.entity-selector');
CJSCore::Init(array("jquery"));
/**
 * @var $arResult
 *
 */
$products = json_encode($arResult['PRODUCTS']);
$measures = json_encode($arResult['MEASURES']);
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
						<div class="select_container" id="container_<?=$productsCount?>">
							<div class="select select_div">
								<select name="PRODUCTS[]" id="PRODUCT_<?=$productsCount?>" class="product_select">
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
									<?php foreach ($arResult['MEASURES'] as $measure):?>
										<option <?=($productSelect['MEASURE_ID'] === $measure['ID'])? 'selected':''?>
												value="<?=$measure['ID']?>"
										>
											<?=$measure['TITLE']?>
										</option>
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
							<button class="button is-primary">
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
	const measures = JSON.parse('<?=$measures;?>');
	const body = document.getElementById("container");
    const imgInp=document.getElementById("img_inp");
    const imgPre=document.getElementById("img_pre");
	var textareaCount = <?=$stepsSize?>;
	var productsCount = <?=$productsSize?>;
	const stepContainer = document.getElementById("step_container")

	function createSelect() {
		productsCount++;
		const select = document.createElement("select");
		const measure_select = document.createElement("select");
		const input = document.createElement("input");
		const div = document.createElement("div");
		const div2 = document.createElement("div");
		const container = document.createElement("div");
		select.id = `PRODUCT_${productsCount}`;
		select.name = `PRODUCTS[]`;

		measure_select.id = `MEASURE_${productsCount}`;
		measure_select.name = `MEASURES[]`;

		input.id = `PRODUCT_QUANTITY_${productsCount}`;
		input.required = true;
		input.name = `PRODUCTS_QUANTITY[]`;

		select.className = `product_select`;
		input.className = `input product_input`;
		container.className = `select_container`
		container.id = `container_${productsCount}`;
		div.className = `select select_div`;
		div2.className = `select select_div`;

		div.appendChild(select);
		div2.appendChild(measure_select);
		container.appendChild(div);
		container.appendChild(input);
		container.appendChild(div2);
		body.appendChild(container);

		for (let i = 0; i < products.length; i++) {
			const option = document.createElement("option");
			option.value = products[i].ID;
			option.text = products[i].NAME;
			select.add(option);
		}
		for (let i = 0; i < measures.length; i++) {
			const option = document.createElement("option");
			option.value = measures[i].ID;
			option.text = measures[i].TITLE;
			measure_select.add(option);
		}
	}

	function deleteSelect() {
		const element = document.getElementById(`container_${productsCount}`);
		element.remove();
		productsCount--;
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
		}
	}

	function deleteStep() {
		const element = document.getElementById(`textarea-${textareaCount}`);
		element.remove();
		textareaCount--;
	}
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            imgPre.src = URL.createObjectURL(file)
        }
    }

</script>
