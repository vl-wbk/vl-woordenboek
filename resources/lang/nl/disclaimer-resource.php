<?php

/**
 * Language strings for the Disclaimer feature.
 *
 * This file centralizes all user-facing text used by the Disclaimer
 * management UI to enable easy translation and maintenance.
 *
 * Structure (top-level keys):
 * - Policy:	access control messages shown when a user lacks permission to perform an action (view, create, update, delete, etc.).
 * - Form:	form-related text grouped by sections, including field labels and placeholders to guide users.
 * - Status-labels:	human-friendly names for different disclaimer types or statuses (e.g., warning, default, danger), used for visual cues.
 * - Infolist: labels for displaying detailed disclaimer information in read-only views.
 * - Table: text for the tabular listing of disclaimers (heading, description, empty state, column headers, and per-row actions).
 * - Header-actions: labels for primary actions in the page header (e.g., create).
 *
 * Note: this file is intended to be language-specific. If you need to support additional languages, provide a separate file with the same keys but translated values.
 */
return [
	/**
	 * Messages for access denial.
	 * These messages are displayed to users when they try to perform an action without the required permissions (e.g., viewing, creating, or deleting an item).
	 */
	'policy' => [
		'deny-messages' => [
			'before' => 'U hebt geen machtiging om het systeem dat de artikelen beheerd te gebruiken.',
			'viewAny' => 'U hebt geen machtiging om een overzicht van disclaimers te bekijken',
			'view' => 'U hebt geen machtiging om de informatie van een disclaimer te bekijken',
			'create' => 'U hebt geen machtiging om een disclaimer aan te maken',
			'update' => 'U hebt geen machtiging om een disclaimer aan te passen',
			'delete' => 'U hebt geen machtiging om een disclaimer te verwijderen',
			'deleteAny' => 'U hebt geen machtiging om meerdere disclaimers te verwijderen',
		],
	],
	
	/**
	 * Form section definitions.
	 * This part of the array groups related form fields and provides titles and descriptions for each section to guide the user.
	 */
	'form' => [
		'sections' => [
			'disclaimer-info' => [
				'title' => 'Disclaimer informatie',
				'description' => 'Alle gegevens en configuratie die gebruikt zal worden om de disclaimer te tonen aan de eindgebruiker die het Vlaams woordenboek raadpleegt.',
				'fields' => [
					'message' => [
						'label' => 'Disclaimer melding',
						'placeholder' => 'Vermeld kort wat je wenst te vermelding richting de gebruiker',
					],
				],
			],
			'management-info' => [
				'title' => 'Beheersinformatie',
				'description' => 'De nodige registraties van interne gegevens die ons toelaat de disclaimers te beheren en te vermelden hoe we de geregistreerde disclaimer wensen te gebruiken.',
				'fields' => [
					'name' => 'Naam',
					'description' => [
						'label' => 'Beschrijving',
						'placeholder' => 'Beschrijf kort waarover de disclaimer gaat zodat het duidelijk is voor andere vrijwilligers',
					],
					'usage' => [
						'placeholder' => 'Beschrijf kort in welke omstandigheden de disclaimer te gebruiken is',
						'label' => 'Gebruikscriteria',
					],
				],
			],
		],
	],
	
	/**
	 * Human-readable labels for different disclaimer statuses or types.
	 * These are often used to categorize disclaimers visually in the UI (e.g., with colors).
	 */
	'status-labels' => [
		'warning' => 'Waarschuwing disclaimer',
		'default' => 'Standaard disclaimer',
		'danger' => 'Gevaren disclaimer',
	],
	
	/**
	 * Labels for information displayed in a detailed view of a disclaimer.
	 * These are used to label fields on read-only pages.
	 */
	'infolist' => [
		'disclaimer-info-tab' => [
			'label' => 'Disclaimer informatie',
			'entries' => [
				'type' => 'Disclaimer type',
				'message' => 'Melding',
			],
		],
		'internal-description-tab' => [
			'label' => 'Interne beschrijving',
		],
		'usage-guideline-tab' => [
			'label' => 'Gebruiksrichtlijn',
		],
	],
	
	/**
	 * Strings related to the table view.
	 * This includes the table heading, description, empty state messages, column headers, and action labels for each row.
	 */
	'table' => [
		'heading' => 'Disclaimers',
		'description' => 'Disclaimers zijn bedoeld om gebruikers snel extra informatie te geven.',
		'empty-state' => [
			'heading' => 'Geen disclaimer(s) aangemaakt',
			'description' => 'Momenteel zijn er geen disclaimers aangemaakt en of gevonden onder de matchende de gegeven criteria.',
		],
		'columns' => [
			'name' => 'naam',
			'article-count' => 'aantal koppelingen',
			'description' => 'beschrijving',
			'created-at' => 'aangemaakt op',
		],
		'actions' => [
			'view-action' => [
				'label' => 'bekijken',
			],
			'edit-action' => [
				'label' => 'bewerken',
			],
			'delete-action' => [
				'label' => 'verwijderen',
				'modal' => [
					'description' => 'U staat op het punt om een disclaimer te verwijderen. Bij het verwijderen zal deze worden losgekoppeld van alle artikelen. Weet u zeker dat je dit wilt doen?',
				],
			],
		],
	],
	
	/**
	 * Strings for actions or buttons located in the page header.
	 * These are typically used for global actions like creating a new item.
	 */
	'header-actions' => [
		'create' => [
			'label' => 'Disclaimer aanmaken',
		],
	],
];