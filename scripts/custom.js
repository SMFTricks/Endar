/** Search  **/
const search_form = document.querySelector('form.custom_search');
search_form.querySelector('button').addEventListener('click', event => {
	if (!search_form.classList.contains('open')) {
		event.preventDefault();
		document.querySelector('form.custom_search').classList.toggle('open');
		document.querySelector('form.custom_search input[name="search"]').focus();
	}
	if (search_form.classList.contains('open')) {
		console.log("might be submitting");
		// Escape
		document.body.addEventListener('keyup', e => {
			if (e.key === 'Escape')
				document.querySelector('form.custom_search').classList.remove('open');
		});
		// Mouse
		document.body.addEventListener('mouseup', e => {
			if (e.target.parentElement !== document.querySelector('form.custom_search') && e.target.parentElement !== document.querySelector('form.custom_search select') && e.target.parentElement !== event.target.parentElement)
				document.querySelector('form.custom_search').classList.remove('open');
		})
	}
});
search_form.querySelector('button i').addEventListener('click', event => {
	if (search_form.classList.contains('open')) {
		event.stopPropagation();
	}
});