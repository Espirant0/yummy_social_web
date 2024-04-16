<?php global $APPLICATION;
/**
 * @var array $arParams;
 * @var array $arResult
 */

$dailyRecipe = $arResult['DAILY_RECIPE'];
?>

<div class="content">
	<div class="columns">
		<div class="column is-three-fifths">
			<div class="search_line">
				<form action="/" method="get">
					<?=$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
						'FILTER_ID' => $arParams['FILTER_ID'],
						'FILTER' => $arParams['FILTER'],
						'ENABLE_LIVE_SEARCH' => true,
						'ENABLE_LABEL' => true
					]);?>
				</form>
			</div>
            <?php foreach($arResult['RECIPES'] as $recipe):?>
				<div class="card recipe_card">
					<a href="/detail/<?=$recipe['ID']?>/">
					<img
                        <?php if (isset($recipe['IMAGE'])):?>
                        src="<?=$recipe['IMAGE']?>"
                        <?php else:?>
                        src="<?=$arParams['IMAGE']?>"
                        <?php endif?>
						alt="https://bulma.io/assets/images/placeholders/1280x960.png"
						class="recipe_img"
                    />
					<div class="card-content">
						<div class="media">
							<div class="media-left">
							</div>
							<div class="media-content">
								<div class="title_line">
									<p class="title is-4 "><?=$recipe['TITLE']?></p>
									<p class="likes"><?=$recipe['LIKES_COUNT']?> ❤</p>
								</div>
								<p class="subtitle is-6"><?=$recipe['DESCRIPTION']?>...</p>
							</div>
						</div>
						<div class="content">
							<div class="columns">
								<div class="column has-text-weight-bold">Калории</div>
								<div class="column has-text-weight-bold">Белки / Жиры / Углеводы</div>
								<div class="column has-text-weight-bold">Время приготовления</div>
							</div>
							<div class="columns">
								<div class="column"><?=$recipe['CALORIES']?> </div>
								<div class="column"><?=$recipe['PROTEINS']?> / <?=$recipe['FATS']?> / <?=$recipe['CARBS']?></div>
								<div class="column"><?=$recipe['TIME']?> мин</div>
							</div>
						</div>
					</div>
					</a>
				</div>
            <?php endforeach;?>
		</div>
		<div class="column right_col is-one-quarter is-offset-1">
			<div class="daily_recipe">
				<form action="/create/" method="get" target="_blank" class="create_btn">
					<button class="button is-success">Добавить рецепт</button>
				</form>
				<p class="title is-4 has-text-centered">Рецепт дня</p>
				<a href="/detail/<?=$dailyRecipe['ID']?>/">
					<div class="card">
						<img
							<?php if (isset($dailyRecipe['IMAGE'])):?>
								src="<?=$dailyRecipe['IMAGE']?>"
							<?php else:?>
								src="<?=$arParams['IMAGE']?>"
							<?php endif?>
							alt="https://bulma.io/assets/images/placeholders/1280x960.png"
						/>
						<div class="card-content">
							<p class="title is-4"><?=$dailyRecipe['TITLE']?></p>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
    <a href="/?page=<?=$arResult['PAGES'][0]?>">НАЗАД</a>
    <a href="/?page=<?=$arResult['PAGES'][1]?>">ВПЕРЕД</a>
</div>