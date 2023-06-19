import m from 'mithril';

import IconSideBarExpand from '@/assets/images/sidebar_hint.png';
import PCNLogo from '@/assets/images/PCNLogo.png';

import '@/css/components/SidePanel.css';

class PanelLink {
	view(vnode) {
		return m('li.side_panel__body-link', {
			class: [
				`${m.route.param('map').toUpperCase() == vnode.attrs.map.toUpperCase() ? 'side-panel__body-link--selected': ''}`
			],
			onclick: () => { m.route.set('/:map', { map: vnode.attrs.map.toLowerCase() }); },
		}, [
			m('div', vnode.attrs.alias || vnode.attrs.map),
			m('div', { style: 'font-size: 10px' }, vnode.attrs.when || '—')
		]);
	}
}

export default class SidePanel {
	oninit() {
		this.expanded = true;
	}
	
	view() {
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
					m(PanelLink, { map: 'Aincrad', }),
					m(PanelLink, { map: 'Creative', when: 'July 2019 - December 2022' }),
					m(PanelLink, { map: 'Lobby', when: 'May 22, 2019 - December 2022' }),
					// m(PanelLink, { map: '2022lts', alias: 'Long Term Survival (LTS)', when: 'December 2021 - December 2022' }),
					m(PanelLink, { map: '2019lts', alias: 'Long Term Survival (LTS/PvE)', when: 'January 2019 - December 2021' }),
					m(PanelLink, { map: 'Trench', when: 'October 2019 - December 2022' }),
				]),
				m('div.side-panel__footer', m('a.side-panel__footer-link', { href: 'https://goodbye.peacefulcraft.net' }, 'Map Downloads'))
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
