<?php

use Up\Yummy\Repository\ValidationRepository;

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;
		$user_id=$USER->GetID();
		$title=ValidationRepository::validateString(request()['NAME'],50);
		$description=ValidationRepository::validateString(request()['DESCRIPTION'],10000);
		$time= request()['TIME'];
		if($title===null||$description===null||ValidationRepository::validatePositiveInteger($time)===false)
		{
			LocalRedirect('/404/');
		}
		$RecipeID=Up\Yummy\Repository\RecipeRepository::addRecipe($title,$description,$time,$user_id);
		$ImageID=\Up\Yummy\Repository\ImageRepository::validateImage();
		\Up\Yummy\Model\ImagesTable::add(['PATH'=>$ImageID,'RECIPE_ID'=>$RecipeID,'IS_COVER'=>1]);
		LocalRedirect('/');
		$this->includeComponentTemplate();
	}

}