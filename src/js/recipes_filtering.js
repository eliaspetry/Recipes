const selectors = {
	orderBySelect: document.querySelector('#entry-order-by'),
	difficultyFilterSelect: document.querySelector('#entry-difficulty-filter'),
	categoryFilterSelect: document.querySelector('#entry-category-filter')
};

let url = new URL(window.location.href);

selectors.orderBySelect.addEventListener('change', event => {
	url.searchParams.set('order_by', event.target.value);
	window.location.href = url;
});

selectors.difficultyFilterSelect.addEventListener('change', event => {
	if (event.target.value === 'all') {
		url.searchParams.delete('difficulty');
	} else {
		url.searchParams.set('difficulty', event.target.value);
	}

	window.location.href = url;
});

selectors.categoryFilterSelect.addEventListener('change', event => {
	if (event.target.value === 'all') {
		url.searchParams.delete('category');
	} else {
		url.searchParams.set('category', event.target.value);
	}

	window.location.href = url;
});
