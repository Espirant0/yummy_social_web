<?php
\Bitrix\Main\UI\Extension::load('up.yummy-selector');
\Bitrix\Main\UI\Extension::load('ui.entity-selector');
/**
 * @var $arResult
 *
 */
$arraySize=$arResult['SIZE'];
$recipe=$arResult['RECIPE'];
?>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">ИЗМЕНИТЬ РЕЦЕПТ</p>
		<form action="/update/<?=$recipe['ID']?>/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" placeholder="Название рецепта" value="<?=$recipe['TITLE']?>" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" required name= "DESCRIPTION" ><?=$recipe['DESCRIPTION']?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="number" value="<?=$recipe['TIME']?>" placeholder="Время приготовления" required>
						</p>
					</div>
				</div>
			</div>
            <div id="step_container">
                <?php foreach($arResult['STEPS'] as $step):?>
                <textarea name = "STEPS[]" id="textarea-<?=$step['STEP']?>">
                    <?=$step['DESCRIPTION']?>
                </textarea>
                <?php endforeach;?>
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
                                <input type="file" name="IMAGES">
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

	<script>
		(function() { const tagSelector = new BX.UI.EntitySelector.TagSelector({
			textBoxAutoHide: true,
			textBoxWidth: 350,
			maxHeight: 99,
			placeholder: 'введите название элемента',
			addButtonCaption: 'Добавить элемент',
			addButtonCaptionMore: 'Добавить еще',
			showCreateButton: true,
			items: [
				{ id: 1, entityId: 'products', textColor: 'orange', title: 'Василий Иванов' },
			],
			events: {
				onBeforeTagAdd: function(event) {
					const selector = event.getTarget();
					const { tag } = event.getData();
					if (tag.getTitle() === 'xxx')
					{
						event.preventDefault();
					}
				},
				onBeforeTagRemove: function(event) {
					const selector = event.getTarget();
					const { tag } = event.getData();
					if (tag.getTitle() === 'aaa')
					{
						event.preventDefault();
					}
				},
				onEnter: (event) => {
					const selector = event.getTarget();
					const value = selector.getTextBoxValue();
					value.split(' ').forEach(function(title) {
						selector.addTag({
							id: id++,
							title: title,
							entityId: 'xxx',
						});
					});
				}
			},
		});
			tagSelector.renderTo(document.getElementById('test1'))})();
	</script
</div>

<script>
    var textareaCount=<?=$arraySize?>;
    const stepContainer=document.getElementById("step_container")
    function createStep()
    {
        if(textareaCount<10)
        {
            const textarea = document.createElement('textarea');
            //textarea.setAttribute('name', 'textarea-' + textareaCount);
            textarea.name = `STEPS[]`
            textareaCount++;
            textarea.id = `textarea-${textareaCount}`
            stepContainer.appendChild(textarea);
        }

    }
    function deleteStep()
    {
        const element = document.getElementById(`textarea-${textareaCount}`);
        element.remove();
        textareaCount--;
    }
</script>
