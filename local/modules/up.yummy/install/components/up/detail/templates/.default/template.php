<?php global $APPLICATION;
/**
 * @var array $arResult
 * @var array $arParams ;
 */

\Bitrix\Main\UI\Extension::load('up.detail');

$recipe = $arResult['RECIPE'];
?>
<form action="/" method="get" class="create_btn">
	<button class="button is-success" id="detail_comeback_btn">На главную</button>
</form>
<div class="content">
	<p class="title recipe_title"><?= $recipe['TITLE'] ?></p>
	<div class="columns">
		<div class="column is-two-fifths recipe_image_container">
			<img class="recipe_img" <?php if (isset($recipe['IMAGE'])): ?>
					src="<?= $recipe['IMAGE'] ?>"
				<?php else: ?>
				 src="<?= $arParams['IMAGE'] ?>"
			<?php endif ?>"/>
		</div>
		<div class="column right_col">
			<div class="buttons upper_buttons">
				<a href="/?apply_filter=Y&AUTHOR_ID=<?= $recipe['AUTHOR_ID'] ?>"
				   class="author_link"
				   id="author_link"
				>
					<div class="author_image"> ЛК</div>
				</a>
				<div class="likes_container">
					<div id="likes_counter" class="likes"></div>
				</div>
					<form class="featured" method="post">
						<input type="hidden" name="recipeId" value="<?= $recipe['ID'] ?>">
						<button class="button is-success" type="button" id="add_to_featured_btn">В избранное</button>
					</form>
					<form class="like" method="post">
						<input type="hidden" name="recipeId" value="<?= $recipe['ID'] ?>">
						<button class="button" type="button" id="like_btn">Лайк</button>
					</form>
			</div>
			<p class="title">Ингредиенты</p><br>
			<div class="column is-half is-offset-one-quarter products">
				<table class="table ">
					<thead>
					<tr>
						<th>Продукт</th>
						<th>Количество</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($arResult['PRODUCTS'] as $product): ?>
						<tr>
							<td><?= $product['TITLE'] ?></td>
							<?php if ($product['MEASURE_NAME'] === "По вкусу"): ?>
								<td> <?= $product['MEASURE_NAME'] ?></td>
							<?php else: ?>
								<td><?= $product['VALUE'] ?> <?= $product['MEASURE_NAME'] ?></td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="container bottom_content">
		<div class="recipe_calories">
			<div class="bottom_buttons">
				<?php if ($recipe['AUTHOR_ID'] == $arResult['AUTHOR_ID']): ?>
					<form action="/delete/" method="post">
						<input type="hidden" name="deleteId" value="<?= $recipe['ID'] ?>">
						<button class="button is-danger" id="delete_recipe_btn"
								onclick="return window.confirm('Вы уверены, что хотите удалить этот рецепт?');">Удалить
							рецепт
						</button>
					</form>
				<?php endif; ?>
				<div class="columns calories">
					<div class="column">
						<h5>Калории</h5>
						<p><?= $recipe['CALORIES'] ?></p>
					</div>
					<div class="column">
						<h5>Белки</h5>
						<p><?= $recipe['PROTEINS'] ?></p>
					</div>
					<div class="column">
						<h5>Жиры</h5>
						<p><?= $recipe['FATS'] ?></p>
					</div>
					<div class="column">
						<h5>Углеводы</h5>
						<p><?= $recipe['CARBS'] ?></p>
					</div>
					<div class="column">
						<h5>Время</h5>
						<p><?= $recipe['TIME'] ?> мин</p>
					</div>
				</div>
				<?php if ($recipe['AUTHOR_ID'] == $arResult['AUTHOR_ID']): ?>
					<form action="/update/<?= $recipe['ID'] ?>/" method="get">
						<button class="button is-warning" id="edit_recipe_btn">Изменить рецепт</button>
					</form>
				<?php endif; ?>
			</div>
		</div>
		<div class="description_container notification is-info">
			<p class="title">Описание</p><br>
			<?= $recipe['DESCRIPTION'] ?>
		</div>
		<div class="step_container   container instruction">
			<?php foreach($arResult['STEPS']as $step):?>
			<div class="notification is-primary">
                <div>Шаг номер <?=$step['STEP']?></div>
				<?=$step['DESCRIPTION']?>
			</div>
            <?php endforeach;?>
		</div>
		<div class="container notification">
			<div class="notification column is-half is-offset-one-quarter">
				<?=
				$APPLICATION->IncludeComponent(
					"bitrix:forum.comments",
					"bitrix24",
					[
						"FORUM_ID" => 1,
						"ENTITY_TYPE" => "s1",
						"ENTITY_ID" => $recipe["ID"],
						"ENTITY_XML_ID" => "RECIPE" . $recipe["ID"],
						"URL_TEMPLATES_PROFILE_VIEW" => "/company/personal/user/#UID#/",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => 0,
						"IMAGE_HTML_SIZE" => 400,
						"MESSAGES_PER_PAGE" => 5,
						"PAGE_NAVIGATION_TEMPLATE" => "arrows",
						"DATE_TIME_FORMAT" => \Bitrix\Tasks\UI::getDateTimeFormat(),
						"SHOW_MODERATION" => "Y",
						"SHOW_AVATAR" => "Y",
						"SHOW_RATING" => "Y",
						"RATING_TYPE" => "like",
						"PREORDER" => "N",
						"FILES_COUNT" => 10,
						"AUTOSAVE" => true,
						"PERMISSION" => "M",
						"MESSAGE_COUNT" => 3,
					]
				);
				?>
			</div>
		</div>
	</div>
</div>
</div>

<script>
	BX.ready(function (){
		window.YummyDetail = new BX.Up.Yummy.Detail({
			user: <?=$arResult['AUTHOR_ID']?>,
			recipe: <?= $recipe['ID'] ?>
		});
	});
</script>