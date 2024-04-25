import {Type, Tag, Loc} from 'main.core';


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

		this.currentDay = options.currentDay;
		this.user = options.userId;
		this.plannerList = [];
		this.courseList = [];
		this.reload();
	}

	reload() {
		this.loadList()
			.then(plannerList => {
				this.plannerList = plannerList;
				this.render();
			});
		this.loadCourses()
			.then(courseList => {
				this.courseList = courseList;
				this.render();
			})
	}

	loadList() {
		return new Promise((resolve, reject) => {
			BX.ajax.runAction(
				'up:yummy.planner.getList',
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

	render() {
		this.rootNode.innerHTML = '';

		const daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
		const currentDate = this.currentDay;
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
						plannerData.date_of_plan === dateData.date + 'T03:00:00+03:00' &&
						plannerData.course_name === courseData.TITLE &&
						plannerData.owner_id === this.user
					);
				});
				return matchingRecipes.map(recipe => recipe.recipe_name).join(', ');
			});

			const planRow = Tag.render`
     <tr>
        <th class="is-info">${courseData.TITLE}</th>
        ${recipes.map((recipe, index) => {
				const date = dates[index].date;
				const params = `course=${encodeURIComponent(courseData.TITLE)}&date=${encodeURIComponent(date)}`;
				return Tag.render`
            <td>
             <a href="?${params}#win1">${recipe}</a>
            </td>
         `;
			})}
     </tr>
    `;
			this.rootNode.appendChild(planRow);
		});

		const headerRow = Tag.render`
    <tr>
     <th></th>
     ${dates.map(dateData => Tag.render`
        <th class="is-info">${dateData.date} (${dateData.dayOfWeek})</th>
     `)}
    </tr>
`;
		this.rootNode.insertBefore(headerRow, this.rootNode.firstChild);
	}
}