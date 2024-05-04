<?php global $APPLICATION;
/**
 * @var array $arResult
 * @var array $arParams
 */

\Bitrix\Main\UI\Extension::load('up.detail');
\Bitrix\Main\UI\Extension::load("ui.buttons.icons");

$recipe = $arResult['RECIPE'];
?>
<form action="/" method="get" class="create_btn">
	<button class="ui-btn ui-btn-success" id="detail_comeback_btn">На главную</button>
</form>
<div class="content">
	<p class="title recipe_title"><?= $recipe['TITLE'] ?></p>
	<div class="columns">
		<div class="column is-one-third recipe_image_container">
			<img class="recipe_img" <?php if (isset($recipe['IMAGE'])): ?>
					src="<?= $recipe['IMAGE'] ?>"
				<?php else: ?>
				 src="<?= $arParams['IMAGE'] ?>"
			<?php endif ?>"/>
		</div>
		<div class="column right_col">
			<div class="buttons upper_buttons">
				<a href="/?apply_filter=Y&AUTHOR_ID=<?= $recipe['AUTHOR_ID'] ?>"
				   id="author_link"
				>
					<div class="author_link">
						<div class="ui-icon ui-icon-common-user author_image"><i></i></div>
						<div class="author_name">
							<?= $recipe['AUTHOR_NAME'] ?> <?= $recipe['AUTHOR_SURNAME'] ?>
						</div>
					</div>
				</a>
				<form class="featured" method="post">
					<input type="hidden" name="recipeId" value="<?= $recipe['ID'] ?>">
					<button type="button" id="add_to_featured_btn" class="ui-btn ui-btn-lg ui-btn-wait">В избранное
					</button>
				</form>
				<form class="like" method="post">
					<input type="hidden" name="recipeId" value="<?= $recipe['ID'] ?>">
					<button class="ui-btn ui-btn-lg ui-btn-wait" id="like_btn" type="button">Мне нравится<i
							id="likes_counter" class="ui-btn-counter"></i></button>
				</form>
			</div>
			<div class="column products">
				<div class="products_table_inner">
					<p class="title is-4">Ингредиенты</p>
					<table class="table products_table notification">
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
	</div>
	<div class="container bottom_content">
		<div class="recipe_calories">
			<div class="bottom_buttons">
				<?php if ($recipe['AUTHOR_ID'] == $arResult['AUTHOR_ID']): ?>
					<form action="/delete/" method="post">
						<input type="hidden" name="deleteId" value="<?= $recipe['ID'] ?>">
						<button class="ui-btn ui-btn-danger ui-btn-icon-remove" id="delete_recipe_btn"
								onclick="return window.confirm('Вы уверены, что хотите удалить этот рецепт?');">Удалить
							рецепт
						</button>
					</form>
				<?php endif; ?>
				<div class="columns calories notification">
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
						<button class="ui-btn ui-btn-icon-edit ui-btn-primary" id="edit_recipe_btn">Изменить рецепт
						</button>
					</form>
				<?php endif; ?>
			</div>
		</div>
		<div class="description_container content">
			<h1>Описание</h1><br>
			<div class="notification description">
				<?= $recipe['DESCRIPTION'] ?>
			</div>
		</div>
		<h1>Пошаговая инструкция</h1>
		<div class="step_container container instruction">
			<?php foreach ($arResult['STEPS'] as $step): ?>
				<div class="notification step">
					<p class="title is-5 step_title">Шаг <?= $step['STEP'] ?></p>
					<?= $step['DESCRIPTION'] ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="container notification comments">
			<p class="title is-4 step_title">Комментарии</p>
			<div class="comments_component">
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
	BX.ready(function () {
		window.YummyDetail = new BX.Up.Yummy.Detail({
			user: <?=$arResult['AUTHOR_ID']?>,
			recipe: <?= $recipe['ID'] ?>
		});
	});
</script>