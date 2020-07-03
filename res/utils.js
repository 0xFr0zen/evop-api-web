function Company(name, tables) {
	this.name = name;
	this.tables = tables;
	this.html = () => {
		let card = document.createElement('div');
		card.className = 'mdc-card my-card';
		let cardContentHolder = document.createElement('div');
		cardContentHolder.className = 'my-card-content';

		let cardLabel = document.createElement('span');
		cardLabel.className = 'my-card-label';
		$(cardLabel).text(this.name);

		let cardInformation = document.createElement('span');
		cardInformation.className = 'my-card-information';
		$(cardInformation).text('tables: ' + this.tables);

		$(cardContentHolder).append(cardLabel);
		$(cardContentHolder).append(cardInformation);

		let action1 = ActionMaker('Show', 'arrow_forward');
		$(action1).on('click', () => {
			let openlink = './company/' + this.name;
			lastopenedWindow = window.open(openlink);
			$(lastopenedWindow).on('close', loadCompanies);
		});
		let cardActionHolder = document.createElement('div');

		cardActionHolder.className = 'mdc-card__actions my-card-action-holder';

		$(cardActionHolder).append(action1);

		$(card).append(cardContentHolder);
		$(card).append(cardActionHolder);
		return card;
	};
}

function ActionMaker(title, text) {
	let action1 = document.createElement('button');
	action1.className =
		'material-icons-round mdc-icon-button mdc-card__action mdc-card__action--icon my-card-action-button';
	$(action1).attr('title', title);
	$(action1).text(text);
	return action1;
}
