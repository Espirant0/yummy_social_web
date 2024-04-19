<?php
/**
 * @var array $arParams ;
 * @var array $arResult
 */

\Bitrix\Main\UI\Extension::load('ui.entity-selector');

$products = json_encode($arResult['PRODUCTS']);
$measures = json_encode($arResult['MEASURES']);

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
							<input class="input" name="TIME" type="text" pattern="[0-9]{,3}"
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
							<input type="file" name="IMAGES">
						</p>
					</div>
				</div>
			</div>
			<div class="product_container">
				<div id="container" class="products_selects">

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
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" onClick="createStep()">Добавить шаг
					</button>
				</div>
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" onClick="deleteStep()">Удалить шаг
					</button>
				</div>
			</div>
			<!--<input type="hidden" name="test1" value=""/>
			<div id="test1" name="test1"></div>-->
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button type="submit" class="button is-primary">
								Добавить рецепт
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>


</div>
<!--<script>
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
</script>-->
<script>
	const products = JSON.parse('<?=$products;?>');
	const measures = JSON.parse('<?=$measures;?>');
	const body = document.getElementById("container");
	const stepContainer = document.getElementById("step_container")
	let textareaCount = 0;
	let selectCount = 0;

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
		const element = document.getElementById(`container_${selectCount}`);
		element.remove();
		selectCount--;
	}

	function createStep() {
		if (textareaCount < 10) {
			textareaCount++;
			const textarea = document.createElement('textarea');
			textarea.required = true;
			textarea.name = `STEPS[]`;
			textarea.id = `textarea-${textareaCount}`;
			stepContainer.appendChild(textarea);
		}

	}

	function deleteStep() {
		const element = document.getElementById(`textarea-${textareaCount}`);
		element.remove();
		textareaCount--;
	}
</script>
