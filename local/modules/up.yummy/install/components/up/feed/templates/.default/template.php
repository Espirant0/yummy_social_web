<?php
/**
 * @var array $arParams;
 * @var array $arResult
 */
?>

<div class="content">
	<div class="columns">
		<div class="column is-three-fifths">
			<div class="search_line">
				<input class="input search_input" type="text" placeholder="Поиск рецепта" />
				<button class="button is-light ml-3 search_btn">Искать</button>
			</div>
            <?php foreach($arResult['RECIPES'] as $recipe):?>
			<a href="/detail/<?=$recipe['ID']?>/">
				<div class="card recipe_card">
					<img
						<!--src="--><?php /*=$arParams['IMAGE']*/?>"
						src="https://bulma.io/assets/images/placeholders/1280x960.png"
						alt="Placeholder image"
						class="recipe_img"/>
					<div class="card-content">
						<div class="media">
							<div class="media-left">
							</div>
							<div class="media-content">
								<div class="title_line">
									<p class="title is-4 "><?=$recipe['TITLE']?></p>
									<p class="likes">6456 ❤</p>
								</div>
								<p class="subtitle is-6"><?=$recipe['DESCRIPTION']?></p>
							</div>
						</div>
						<div class="content">
							<div class="columns">
								<div class="column has-text-weight-bold">Калории</div>
								<div class="column has-text-weight-bold">Белки / Жиры / Углеводы</div>
								<div class="column has-text-weight-bold">Время приготовления</div>
							</div>
							<div class="columns">
								<div class="column">5423423</div>
								<div class="column"><?=$recipe['PROTEINS']?> / <?=$recipe['FATS']?> / <?=$recipe['CARBS']?></div>
								<div class="column"><?=$recipe['TIME']?> мин</div>
							</div>
						</div>
					</div>
				</div>
			</a>
            <?php endforeach;?>
		</div>
		<div class="column right_col is-one-quarter is-offset-1">
			<div class="daily_recipe">
				<form action="/create/" method="get" target="_blank" class="create_btn">
					<button class="button is-success">Добавить рецепт</button>
				</form>
				<p class="title is-4 has-text-centered">Рецепт дня</p>
				<a href="/detail/1/">
					<div class="card">
						<img
							src="https://bulma.io/assets/images/placeholders/1280x960.png"
							alt="Placeholder image"
						/>
						<div class="card-content">
							<p class="title is-4"><?=$arResult['dailyRecipe']?></p>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
    <a href="/?page=<?=$arResult['PAGES'][0]?>">НАЗАД</a>
    <a href="/?page=<?=$arResult['PAGES'][1]?>">ВПЕРЕД</a>
</div>