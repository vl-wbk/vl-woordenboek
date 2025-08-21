<?php

return [
	'tables' => [
		'heading' => 'Gebruikersbeheer',
		'description' => 'In dit overzicht zie je alle geregistreerde gebruikers van het systeem. Je kunt hier gebruikersgegevens bekijken, accounts bewerken, rollen toewijzen of gebruikers verwijderen. Gebruik de zoek- en filteropties om snel de juiste gebruiker te vinden.',
	
		'columns' => [
			'name' => 'Naam',
			'email' => 'E-mail adres',
			'user-type' => 'Gebruikers groep',
			'last-seen-at' => 'Laatste aanmelding',
			'roles' => [
				'label' => 'Gebruikers rol',
				'placeholder' => '- geen toegewezen'
			]
		],
		
		'filters' => [
			'user-type' => 'Gebruikers groep'
		],
	],
	
	'buttons' => [
		'create-user' => 'Gebruiker toevoegen',
	],
	
	'actions' => [
		'deactivate-user' => [
			'label' => 'Deactiveer', 

			'modal' => [
				'heading' => 'Gebruiker deactiveren', 
				'form' => [
					'comment' => 'Reden tot deactivering',
					'expires-at' => 'Verloopt op',
				],
			], 

			'buttons' => [
				'confirm' => 'Bevestigen',
			]
		],

		'reactivate-user' => [
			'label' => 'Reactiveer',

			'modal' => [
				'heading' => 'Gebruiker heractiveren'
			],

			'buttons' => [
				'confirm' => 'Bevestigen'
			],
		],
	]
];