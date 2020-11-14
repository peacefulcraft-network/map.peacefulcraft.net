import m from 'mithril';

import IconSideBarExpand from '@/assets/images/sidebar_hint.png';
import PCNLogo from '@/assets/images/PCNLogo.png';

import '@/css/components/SidePanel.css';

export default class SidePanel {
	oninit() {
		this.expanded = true;
	}
	
	view() {
		console.log(m.route.param('map').toUpperCase() == 'AINCRAD');
		return m('.side-panel', {
			class: [
				`${this.expanded? 'side-panel--expanded' : ''}`
			]
		}, [
			m('.side-panel__body', {
				class: [
					`side-panel__body--${this.expanded? 'expanded' : 'hidden'}`
				],
				onmouseleave: () => { this.expanded = false; }
			}, [
				m('.side-panel__body-header', [
					m('img.side-panel__body-header-logo', {
						src: PCNLogo,
					}),
				]),
				m('ul.side-panel__body-links', [
					/*m('li.side_panel__body-link', {
						class: [
							`${m.route.param('map').toUpperCase() == 'AINCRAD'? 'side-panel__body-link--selected': ''}`
						],
						onclick: () => { m.route.set('/:map', { map: 'aincrad'}); },
					}, 'Aincrad'),*/
					m('li.side_panel__body-link', {
						class: [
							`${m.route.param('map').toUpperCase() == 'CREATIVE'? 'side-panel__body-link--selected': ''}`
						],
						onclick: () => { m.route.set('/:map', { map: 'creative'}); },
					}, 'Creative'),
					/* m('li.side_panel__body-link', {
						class: [
							`${m.route.param('map').toUpperCase() == 'LOBBY'? 'side-panel__body-link--selected': ''}`
						],
						onclick: () => { m.route.set('/:map', { map: 'lobby'}); },
					}, 'Lobby'),
					m('li.side_panel__body-link', {
						class: [
							`${m.route.param('map').toUpperCase() == 'PVE'? 'side-panel__body-link--selected': ''}`
						],
						onclick: () => { m.route.set('/:map', { map: 'pve'}); },
					}, 'PvE'),*/
				])
			]),
			m('img.side-panel__expander-icon',  {
				src: IconSideBarExpand,
				class: [
					`side-panel__expander-icon--${this.expanded? 'hidden' : ''}`,
				],
				onmouseenter: () => { this.expanded = true; },
			})
		]);
	}
}