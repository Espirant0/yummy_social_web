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

		const daysOfWeek = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
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
			
		</tr>`;
		document.getElementById(`product_table`).appendChild(productHeader);
		for (const key in this.productList) {
			const productRow = Tag.render`
			 <tr>
				<td>${this.productList[key][3]}</td>
				<td>${this.productList[key][1]} ${this.productList[key][2]}</td>
			</tr>
			`;
			document.getElementById(`product_table`).appendChild(productRow);
		}

		const headerRow = Tag.render`
			<tr>
				<th class="is-info"></th>
				${dates.map(dateData => Tag.render`
					<th class="is-info table_cell" data-date="${dateData.date}">${this.formatDate(dateData.date)} <br>${dateData.dayOfWeek}</th>
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

		const headerDates = document.querySelectorAll('th[data-date]');
		headerDates.forEach(date => {
			this.loadDailyProductList(date.getAttribute('data-date')).then(productList => {
				date.addEventListener('click', event => {
					this.openProductsView(event, productList);
				});
			});
		});
	}

	formatDate(dateString) {
		let date = new Date(dateString);
		let day = date.getDate();
		let month = date.getMonth() + 1;
		let year = date.getFullYear();

		// Добавляем ведущий ноль, если день или месяц состоят из одной цифры
		if (day < 10) {
			day = "0" + day;
		}
		if (month < 10) {
			month = "0" + month;
		}

		return day + "." + month + "." + year;
	}
	openProductsView(event, products)
	{
		event.preventDefault();
		const target = event.target;
		const table = document.getElementById(`daily_product_table`);
		const productHeader = Tag.render`
		<tr class="popup_table">
			<th>Продукт</th>
			<th>Количество</th>
		</tr>`;
		table.appendChild(productHeader);
		for (const key in products) {
			const productRow = Tag.render`
			 <tr class="popup_table">
				<td>${products[key][3]}</td>
				<td>${products[key][1]} ${products[key][2]}<</td>
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
					}
				}
			}
		)
		popup.setContent(BX('daily_product_table'));
		popup.show();
	}
	openEditForm(event, recipes)
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
		dateInput.className = "ui-ctl-element";
		dateInput.value = this.formatDate(currentTime.toISOString().split("T")[0]);
		dateInput.readOnly = true;

		const course = document.createElement("div");
		course.id = "edit_course";
		course.className = "notification course";
		course.textContent = courseName;

		const recipeDivSelect = document.createElement("div");
		recipeDivSelect.className = "ui-ctl ui-ctl-after-icon ui-ctl-dropdown";

		const iconDiv = document.createElement("div");
		iconDiv.className = "ui-ctl-after ui-ctl-icon-angle";
		recipeDivSelect.appendChild(iconDiv);

		const recipeSelect = document.createElement("select");
		recipeSelect.id = "edit_recipe";
		recipeSelect.className = "ui-ctl-element"
		recipeDivSelect.appendChild(recipeSelect);

		const buttonsDiv = document.createElement("div");
		buttonsDiv.className = "popup_buttons";

		const editButton = document.createElement("button");
		editButton.className = "ui-btn ui-btn-success ui-btn-icon-edit";
		editButton.type = "button";
		editButton.id = "edit_btn";
		editButton.textContent = "Применить";

		const deleteButton = document.createElement("button");
		deleteButton.className = "ui-btn ui-btn-danger ui-btn-icon-remove";
		deleteButton.type = "button";
		deleteButton.id = "delete_btn";
		deleteButton.textContent = "Удалить";

		const emptyListMessage = document.createElement("div");
		emptyListMessage.textContent = `У вас нет рецептов в избранном`;
		emptyListMessage.className = "notification course";
		recipes.forEach(recipesData => {
			let option = document.createElement("option");
			option.value = recipesData.ID;
			option.textContent = recipesData.TITLE;
			recipeSelect.appendChild(option);
		});
		popupForm.appendChild(dateInput);
		popupForm.appendChild(course);
		if(recipes.length !== 0)
		{
			popupForm.appendChild(recipeDivSelect);
			buttonsDiv.appendChild(editButton);
		}
		else {

			popupForm.appendChild(emptyListMessage);
		}
		buttonsDiv.appendChild(deleteButton);
		popupForm.appendChild(buttonsDiv);


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