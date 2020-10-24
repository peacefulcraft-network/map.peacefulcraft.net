import m from 'mithril';

import DynmapEmbed from '@/components/DynmapEmbed.js';
import SidePanel from '@/components/SidePanel.js';

import '@/css/views/Map.css';

export default class Map {
	view() {
		return m('#map-page', [
			m(SidePanel),
			m(DynmapEmbed, {
				src: `https://map.peacefulcraft.net/${m.route.param('map')}/index.html`,
			})
		]);
	}
}