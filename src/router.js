import m from 'mithril';

m.route.prefix = '#!';
m.route(document.body, '/', {
	'/': {
		onmatch: () => {
			m.route.set(':map', { map: 'aincrad'});
		}
	},
	'/:map': {
		onmatch: () => new Promise((resolve) => {
			import(/* webpackChunkName: "Map" */ '@/views/Map.js').then(({ default: Map}) => {
				resolve(Map);
			});
		}),
	},
});