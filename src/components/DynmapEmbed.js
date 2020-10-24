import m from 'mithril';

import '@/css/components/DynmapEmbed.css';

export default class DynmapEmbed {
	view(vnode) {
		return m('iframe.dynmap-embed', {
			src: vnode.attrs.src,
			frameBorder: 0,
		});
	}
}