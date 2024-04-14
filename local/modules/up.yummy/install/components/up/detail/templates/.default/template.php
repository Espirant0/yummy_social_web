<?php global $APPLICATION;
/**
 * @var array $arResult
* @var array $arParams;
 */
$recipe=$arResult['RECIPE'];
?>
<div class="content">
	<div class="search_line">
		<form action="/" method="get">
			<?=$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
				'FILTER_ID' => $arParams['FILTER_ID'],
				'FILTER' => $arParams['FILTER'],
				'ENABLE_LIVE_SEARCH' => true,
				'ENABLE_LABEL' => true
			]);?>
		</form>
		<form action="/create/" class="add_form" method="get" target="_blank">
			<button class="button is-success">Добавить рецепт</button>
		</form>
	</div>
	<p class="title recipe_title"><?=$recipe['TITLE']?></p>
	<div class="columns">
		<div class="column is-two-fifths recipe_image_container">
			<img class="recipe_img" <?php if (isset($recipe['IMAGE'])):?>
                    src="<?=$recipe['IMAGE']?>"
				<?php else:?>
                 src="<?=$arParams['IMAGE']?>"
			<?php endif?>"/>
		</div>
		<div class="column right_col">
			<div class="buttons upper_buttons">
				<a href="" class="author_link">
					<div class="author_image"> ЛК</div>
				</a>
				<div class="likes_container">
					<p class="likes">6456 ❤</p>
				</div>
				<?php if(!$arResult['FEATURED']): ?>
					<form class="featured" action="/featured/" method="post">
						<input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
						<button class="button is-success">В избранное</button>
					</form>
				<?php else:?>
					<form action="/featured/" method="post">
						<input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
						<button class="button is-danger">Убрать из избранного</button>
					</form>
				<?php endif;?>
				<button class="button is-success">Лайк</button>
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
					<?php foreach($arResult['PRODUCTS'] as $product):?>
						<tr>
							<td><?= $product['TITLE']?></td>
                            <?php if($product['MEASURE_NAME']==="По вкусу"):?>
							<td> <?= $product['MEASURE_NAME']?></td>
                            <?php else:?>
                            <td><?= $product['VALUE']?> <?= $product['MEASURE_NAME']?></td>
                        <?php endif;?>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="container bottom_content">
		<div class="recipe_calories">
			<div class="bottom_buttons">
				<?php if($recipe['AUTHOR_ID']==$arResult['AUTHOR_ID']):?>
					<form action="/delete/" method="post">
						<input type="hidden" name="deleteId" value="<?=$recipe['ID']?>">
						<button class="button is-danger" onclick="return window.confirm('Вы уверены, что хотите удалить этот рецепт?');">Удалить рецепт</button>
					</form>
				<?php endif;?>
				<div class="columns calories">
					<div class="column">
						<h5>Калории</h5>
						<p>432423</p>
					</div>
					<div class="column">
						<h5>Белки</h5>
						<p>143232</p>
					</div>
					<div class="column">
						<h5>Жиры</h5>
						<p>32423</p>
					</div>
					<div class="column">
						<h5>Углеводы</h5>
						<p>534534</p>
					</div>
					<div class="column">
						<h5>Время</h5>
						<p><?=$recipe['TIME']?> мин</p>
					</div>
				</div>
				<?php if($recipe['AUTHOR_ID']==$arResult['AUTHOR_ID']):?>
					<button class="button is-warning">Изменить рецепт</button>
				<?php endif;?>
			</div>
		</div>
		<div class="notification is-info">
			<p class="title">Описание</p><br>
			<?=$recipe['DESCRIPTION']?>
		</div>
		<div class="container instruction">
			<div class="notification is-primary">
				Пошаговая инструкция
			</div>
		</div>
		<div class="container">
			<div class="notification is-primary">
				Место под комментарии
			</div>
		</div>
	</div>
</div>
</div>