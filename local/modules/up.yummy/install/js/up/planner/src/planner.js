import {Type, Tag, Loc} from 'main.core';
import {Popup} from 'main.popup';

export class Planner {
	constructor(options = {}) {
		if (Type.isStringFilled(options.rootNodeId)) {
			this.rootNodeId = options.rootNodeId;
		} else {
			throw new Error('Planner: options.rootNodeId required');
		}

		this.rootNode = document.getElementById(this.rootNodeId);
		if (!this.rootNode) {
			throw new Error(`Planner: element with id "${this.rootNodeId}" not found`);
		}
		this.title = options.title;
		this.currentDate = options.currentDate;
		this.start = options.start;
		this.user = options.userId;
		this.plannerList = [];
		this.productList = [];
		this.courseList = [];
		this.recipeList = [];
		this.selectedRecipe = [];
		this.reload();
	}

	reload() {
		this.loadList(this.start)
			.then(plannerList => {
				this.plannerList = plannerList;
				this.render();
			});
		this.loadCourses()
			.then(courseList => {
				this.courseList = courseList;
				this.render();
			});
		this.loadProductList(this.start)
			.then(productList => {
				this.productList = productList;
				this.render();
			});
		this.loadRecipeList()
			.then(recipeList => {
				this.recipeList = recipeList;
				this.render();
			});
	}

	loadList(start) {
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.planner.getList',
				{
					data: {
						start: start,
						user: this.user,
					}
				})
				.then((response) => {
					resolve(response.data);
				})
				.catch((error) => {
					console.error(error);
				})
			;
		});
	}

	loadProductList(start)
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.planner.getProducts',
				{
					data: {
						start: start,
						user: this.user
					}
				})
				.then((response) => {
					resolve(response.data);
				})
				.catch((error) => {
					console.error(error);
				})
			;
		});
	}

	loadDailyProductList(date)
	{
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.planner.getDailyProducts',
				{
					data: {
						date: date,
						user: this.user
					}
				})
				.then((response) => {
					resolve(response.data);
				})
				.catch((error) => {
					console.error(error);
				})
			;
		});
	}

	loadCourses() {
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.planner.getCourses',
				{
					data: {
						apiKey: 'very_secret_key',
					}
				})
				.then((response) => {
					resolve(response.data);
				})
				.catch((error) => {
					console.error(error);
				})
			;
		});
	}

	loadRecipeList() {
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.planner.getRecipeList',
				{
					data: {
						apiKey: 'very_secret_key',
					}
				})
				.then((response) => {
					resolve(response.data);
				})
				.catch((error) => {
					console.error(error);
				})
			;
		});
	}

	render() {
		this.rootNode.innerHTML = '';

		const daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
		const currentDate = this.currentDate;
		const currentDayOfWeek = currentDate.getDay();
		const firstDayOfWeek = new Date(currentDate);
		firstDayOfWeek.setDate(firstDayOfWeek.getDate() - currentDayOfWeek + 1);
		const dates = Array.from({ length: 7 }, (_, index) => {
			const date = new Date(firstDayOfWeek);
			date.setDate(date.getDate() + index);
			const dayOfWeek = daysOfWeek[index];
			return { date: date.toISOString().split('T')[0], dayOfWeek };
		});
		this.courseList.forEach(courseData => {
			const recipes = dates.map((dateData, index) => {
				const matchingRecipes = this.plannerList.filter(plannerData => {
					return (
						plannerData.DATE_OF_PLAN === dateData.date + 'T03:00:00+03:00' &&
						plannerData.COURSE_NAME === courseData.TITLE &&
						plannerData.OWNER_ID === this.user
					);
				});
				return matchingRecipes.map(recipe => recipe.RECIPE_NAME).join(', ');
			});
			const planRow = Tag.render`
			<tr>
				<th class="is-info">${courseData.TITLE}</th>
				${recipes.map((recipe, index) => {
					let cellContent = '+';
					if(recipe !=='')
					{
						cellContent = recipe;
					}
					const date = dates[index].date;
					return Tag.render`
						<td class="table_cell" data-date="${date}" data-target="modal" data-course-name="${courseData.TITLE}" data-course-id="${courseData.ID}">
						${cellContent}
						</td>
					`;
				})}
			</tr>
			`;
			this.rootNode.appendChild(planRow);
		});

		document.getElementById(`product_table`).innerHTML = ``;
		const productHeader = Tag.render`
		<tr>
			<th>Продукт</th>
			<th>Количество</th>
			<th>Мера</th>
			
		</tr>`;
		document.getElementById(`product_table`).appendChild(productHeader);
		for (const key in this.productList) {
			const productRow = Tag.render`
			 <tr>
				<th>${this.productList[key][3]}</th>
				<th>${this.productList[key][1]}</th>
				<th>${this.productList[key][2]}</th>
			</tr>
			`;
			document.getElementById(`product_table`).appendChild(productRow);
		}

		const headerRow = Tag.render`
			<tr>
				<th class="is-info"></th>
				${dates.map(dateData => Tag.render`
					<th class="is-info" data-date="${dateData.date}">${dateData.date} (${dateData.dayOfWeek})</th>
				`)}
			</tr>
		`;
		this.rootNode.insertBefore(headerRow, this.rootNode.firstChild);
		const cells = document.querySelectorAll('td[data-date][data-course-id]');
		cells.forEach(cell => {
			cell.addEventListener('click', event => {
				this.openEditForm(event, this.recipeList, this.selectedRecipe);
			});
		});

		/*const headerDates = document.querySelectorAll('th[data-date]');
		headerDates.forEach(date => {
			date.addEventListener('click', event => {
				this.openProductsView(event);
			});
		});*/
	}
	openProductsView(event)
	{
		event.preventDefault();
		const target = event.target;
		const date = target.getAttribute('data-date');
		this.loadDailyProductList(date).then(productList => {
			this.dailyProductList = productList;
			this.render();
			this.reload();
		});

		const table = document.getElementById(`daily_product_table`);
		const productHeader = Tag.render`
		<tr>
			<th>Продукт</th>
			<th>Количество</th>
			<th>Мера</th>
		</tr>`;
		table.appendChild(productHeader);
		for (const key in this.dailyProductList) {
			const productRow = Tag.render`
			 <tr>
				<th>${this.dailyProductList[key][3]}</th>
				<th>${this.dailyProductList[key][1]}</th>
				<th>${this.dailyProductList[key][2]}</th>
			</tr>
			`;
			table.appendChild(productRow);
		}

		const popup = new BX.PopupWindow(
			`products`, target,
			{
				autoHide : true,
				lightShadow : true,
				closeIcon : true,
				closeByEsc : true,
				offsetLeft: "auto",
				offsetTop: "auto",
				overlay: {backgroundColor: 'black', opacity: '80' },
				events:{
					onPopupClose: function() {
						table.innerHTML = "";
						this.dailyProductList = [];
					}
				}
			}
		)
		popup.setContent(BX('daily_product_table'));
		popup.show();
	}
	openEditForm(event, recipes, selectedRecipe)
	{
		event.preventDefault();
		const target = event.target;
		const date = target.getAttribute('data-date');
		const courseName = target.getAttribute('data-course-name');
		const courseId = target.getAttribute('data-course-id');

		let currentTime = new Date(date);
		const popupForm = document.createElement("form");
		popupForm.method = "post";

		const dateInput = document.createElement("input");
		dateInput.className = "input";
		dateInput.name = "date";
		dateInput.type = "text";
		dateInput.id = "edit_date";
		dateInput.value = currentTime.toISOString().split("T")[0];
		dateInput.readOnly = true;

		const course = document.createElement("div");
		course.id = "edit_course";
		course.className = "container";
		course.textContent = courseName;

		const recipeDivSelect = document.createElement("div");
		recipeDivSelect.className = "select";

		const recipeSelect = document.createElement("select");
		recipeSelect.id = "edit_recipe";

		recipeDivSelect.appendChild(recipeSelect);

		const editButton = document.createElement("button");
		editButton.className = "button";
		editButton.type = "button";
		editButton.id = "edit_btn";
		editButton.textContent = "Изменить";

		const deleteButton = document.createElement("button");
		deleteButton.className = "button";
		deleteButton.type = "button";
		deleteButton.id = "delete_btn";
		deleteButton.textContent = "Удалить";

		recipes.forEach(recipesData => {
			let option = document.createElement("option");
			option.value = recipesData.ID;
			option.textContent = recipesData.TITLE;
			recipeSelect.appendChild(option);
		});

		popupForm.appendChild(dateInput);
		popupForm.appendChild(course);
		popupForm.appendChild(recipeDivSelect);
		popupForm.appendChild(editButton);
		popupForm.appendChild(deleteButton);


		const modal = document.getElementById('modal');
		const div = document.createElement('modal_content');
		div.appendChild(popupForm);
		modal.appendChild(div);
		const popup = new BX.PopupWindow(
			`add_recipe`, target,
			{
				autoHide : true,
				lightShadow : true,
				closeIcon : true,
				closeByEsc : true,
				offsetLeft: "auto",
				offsetTop: "auto",
				overlay: {backgroundColor: 'black', opacity: '80' },
				events:{
					onPopupClose: function() {
						div.remove();
					}
				}
			}
		)
		popup.setContent(BX('modal'));
		popup.show();

		editButton.addEventListener('click', ()=> {
			BX.ajax.runAction(
				'up:yummy.planner.editPlan',
				{
					data: {
						date: date,
						course: courseId,
						recipe: recipeSelect.value,
						user: this.user,
					}
				})
				.then((response) => {
					console.log(`success`);
					this.reload();
				})
				.catch((error) => {
					console.error(error);
				})
			;
			popup.close();
			this.reload();
		});

		deleteButton.addEventListener('click', ()=> {
			BX.ajax.runAction(
				'up:yummy.planner.deletePlan',
				{
					data: {
						date: date,
						course: courseId,
						user: this.user,
					}
				})
				.then((response) => {
					console.log(`success`);
					this.reload();
				})
				.catch((error) => {
					console.error(error);
				})
			;
			popup.close();
			this.reload();
		});
	}
}