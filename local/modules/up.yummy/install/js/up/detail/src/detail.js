import {Type} from 'main.core';

export class Detail
{
	constructor(options = {})
	{
		this.user = options.user;
		this.recipe = options.recipe;
		this.likesCount = options.likesCount;
	}

	reload() {
		this.like(this.user, this.recipe)
			.then(() => {
				this.render();
			});
	}
	like(user, recipe) {
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.detail.like',
				{
					data: {
						recipe: recipe,
						user: user,
					}
				})
				.then((response) => {
					console.log(success);
				})
				.catch((error) => {
					console.error(error);
				})
			;
		});
	}
	render() {}
}
