<?php

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->includeComponentTemplate();
	}

	protected function prepareTemplateParams(): void
	{
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}