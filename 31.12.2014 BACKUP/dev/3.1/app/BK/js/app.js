define([
	'angular',
	'angularRoute',
	'angularAnimate',
	'angularCookies',
	'angularTranslate',
	'angularFoundation',
	'factories',
	'controllers',
	'directives',
	'filters',
	'd3',
	'underscore'
], function (angular) {

	'use strict';

	return angular.module('owloo', ['ngRoute', 'ngAnimate', 'ngCookies', 'owloo.factories', 'owloo.controllers', 'owloo.directives', 'owloo.filters', 'pascalprecht.translate'])

	.config (function ($routeProvider, $locationProvider, $sceDelegateProvider, $httpProvider) {

		$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

		$sceDelegateProvider.resourceUrlWhitelist(['self', 'http://www.owloo.com/dev/3.1/**']);

		$routeProvider

			.when('/test', {
				pageKey: 'home',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/test.html',
				controller: 'TestController',
				resolve: {
					data: function ($q, $route, FacebookPages) {
						// parameters
						var username = 'shakira';
						// filters
						var page  = FacebookPages.getPage(username).then(function (response) { return response.data; });
						// return
						return $q.all([page]).then(function(result) {
							return {
								'page': result[0]
							}
						});
					}
				}
			})

			.when('/', {
				pageKey: 'home',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/home.html',
				controller: 'HomeController',
				resolve: {
/* */				data: function ($q, OwlooUsers, FacebookPages, FacebookCountries, TwitterProfiles, InstagramProfiles) {
						// parameters
						var idiom = 'es';
						// factories
						var totalOwlooUsers  = OwlooUsers.getTotal().then(function (response) { return response.data; });
						var facebookGrowRank = FacebookPages.getGrowRank(idiom).then(function (response) { return response.data; });
						var twitterGrowRank = TwitterProfiles.getGrowRank(idiom).then(function (response) { return response.data; });
						var instagramGrowRank = InstagramProfiles.getGrowRank().then(function (response) { return response.data; });
/* */					var countryGrowRank = FacebookCountries.getGrow(idiom).then(function (response) { return response.data; });
						// return
/* */					return $q.all([totalOwlooUsers, facebookGrowRank, twitterGrowRank, instagramGrowRank, countryGrowRank]).then(function(result) {
							return {
								'totalOwlooUsers': result[0],
								'facebookGrowRank': result[1],
								'twitterGrowRank': result[2],
								'instagramGrowRank': result[3],
/* */							'countryGrowRank': result[4]
							}
						});
					}
				}
			})

			/*
			|--------------------------------------------------------------------------
			| FACEBOOK
			|--------------------------------------------------------------------------
			*/

			.when('/facebook-stats', {
				pageKey: 'facebook-index',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats.html',
				controller: 'FacebookIndexController'
			})

			.when('/facebook-stats/pages/world/:category?/:page?', {
				pageKey: 'facebook-pages-rank-world',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats-pages.html',
				controller: 'FacebookPagesRankController',
				resolve: {
					data: function ($q, $route, FacebookPages) {
						// parameters
						var category = ($route.current.params.category) ? $route.current.params.category : 'all';
						var page = ($route.current.params.page) ? $route.current.params.page : 1;
						// filters
						var rank  = FacebookPages.getRank('world', category, page).then(function (response) { return response.data; });
						var total = FacebookPages.getTotal('world').then(function (response) { return response.data; });
						var countries = FacebookPages.getCountries().then(function (response) { return response.data; });
						var categories = FacebookPages.getCategories().then(function (response) { return response.data; });
						// return
						return $q.all([rank, total, countries, categories]).then(function(result) {
							return {
								'currentWhere': 'world',
								'currentPage': page,
								'currentCategory': category,
								'rank': result[0],
								'total': result[1].total,
								'countries': result[2],
								'categories': result[3]
							}
						});
					}
				}
			})

			.when('/facebook-stats/pages/hispanic/:category?/:page?', {
				pageKey: 'facebook-pages-rank-hispanic',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats-pages.html',
				controller: 'FacebookPagesRankController',
				resolve: {
					data: function ($q, $route, FacebookPages) {
						// parameters
						var category = ($route.current.params.category) ? $route.current.params.category : 'all';
						var page = ($route.current.params.page) ? $route.current.params.page : 1;
						// filters
						var rank  = FacebookPages.getRank('hispanic', category, page).then(function (response) { return response.data; });
						var total = FacebookPages.getTotal('hispanic').then(function (response) { return response.data; });
						var countries = FacebookPages.getCountries().then(function (response) { return response.data; });
						var categories = FacebookPages.getCategories().then(function (response) { return response.data; });
						// return
						return $q.all([rank, total, countries, categories]).then(function(result) {
							return {
								'currentWhere': 'hispanic',
								'currentPage': page,
								'currentCategory': category,
								'rank': result[0],
								'total': result[1].total,
								'countries': result[2],
								'categories': result[3]
							}
						});
					}
				}
			})

			.when('/facebook-stats/pages/country/:country_code/:category?/:page?', {
				pageKey: 'facebook-pages-rank-by-country',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats-pages-country.html',
				controller: 'FacebookPagesRankByCountryController',
				resolve: {
					data: function ($q, $route, FacebookPages) {
						// parameters
						var country_code = $route.current.params.country_code;
						var category = ($route.current.params.category) ? $route.current.params.category : 'all';
						var page = ($route.current.params.page) ? $route.current.params.page : 1;
						// filters
						var rank  = FacebookPages.getRank(country_code, category, page).then(function (response) { return response.data; });
						var total = FacebookPages.getTotal('world').then(function (response) { return response.data; });
						var countries = FacebookPages.getCountries().then(function (response) { return response.data; });
						var categories = FacebookPages.getCategories().then(function (response) { return response.data; });
						// return
						return $q.all([rank, total, countries, categories]).then(function(result) {
							return {
								'countryCode': country_code,
								'currentPage': page,
								'currentCategory': category,
								'rank': result[0],
								'total': result[1].total,
								'countries': result[2],
								'categories': result[3]
							}
						});
					}
				}
			})

			.when('/facebook-stats/pages/:username', {
				pageKey: 'facebook-pages-username',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats-pages-username.html',
				controller: 'FacebookPagesUsernameController',
				resolve: {
					data: function ($q, $route, FacebookPages) {
						// parameters
						var username = $route.current.params.username;
						// filters
						var page  = FacebookPages.getPage(username).then(function (response) { return response.data; });
						// return
						return $q.all([page]).then(function(result) {
							return {
								'page': result[0]
							}
						});
					}
				}
			})


			/*
			|--------------------------------------------------------------------------
			| TWITTER
			|--------------------------------------------------------------------------
			*/

			.when('/twitter-stats', {
				pageKey: 'twitter-stats',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/twitter-stats.html',
				controller: 'TwitterStatsController'
			})

			.when('/twitter-stats/userpage/world/:page?', {
				pageKey: 'twitter-stats-userpage-world',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats-pages.html',
				controller: 'TwitterStatsRankController',
				resolve: {
					data: function ($q, $route, TwitterProfiles) {
						// parameters
						var page = ($route.current.params.page) ? $route.current.params.page : 1;
						// filters
						var rank  = TwitterProfiles.getRank('world', category, page).then(function (response) { return response.data; });
						var total = TwitterProfiles.getTotal('world').then(function (response) { return response.data; });
						// return
						return $q.all([rank, total]).then(function(result) {
							return {
								'currentWhere': 'world',
								'currentPage': page,
								'rank': result[0],
								'total': result[1].total
							}
						});
					}
				}
			})

			.when('/twitter-stats/userpage/hispanic/:page?', {
				pageKey: 'twitter-stats-userpage-world',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/facebook-stats-pages.html',
				controller: 'TwitterStatsRankController',
				resolve: {
					data: function ($q, $route, TwitterProfiles) {
						// parameters
						var page = ($route.current.params.page) ? $route.current.params.page : 1;
						// filters
						var rank  = TwitterProfiles.getRank('world', category, page).then(function (response) { return response.data; });
						var total = TwitterProfiles.getTotal('world').then(function (response) { return response.data; });
						// return
						return $q.all([rank, total]).then(function(result) {
							return {
								'currentWhere': 'world',
								'currentPage': page,
								'rank': result[0],
								'total': result[1].total
							}
						});
					}
				}
			})

			/*
			|--------------------------------------------------------------------------
			| FEATURES
			|--------------------------------------------------------------------------
			*/

			.when('/features', {
				pageKey: 'features',
				templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/features.html',
				controller: 'FeaturesController',
				resolve: {
					data: function ($q, $route, FacebookPages, TwitterProfiles, InstagramProfiles, OwlooUsers) {
						// factories
						var facebookPages = FacebookPages.getTotal('world').then(function (response) { return response; });
						var twitterProfiles = TwitterProfiles.getTotal('world').then(function (response) { return response; });
						var instagramProfiles = InstagramProfiles.getTotal('world').then(function (response) { return response; });
						var owlooUsers = OwlooUsers.getTotal().then(function (response) { return response; });
						// return
						return $q.all([facebookPages, twitterProfiles, instagramProfiles, owlooUsers]).then(function(result) {
							return {
								'totalFacebookPages': result[0].data.total,
								'totalTwitterProfiles': result[1].data,
								'totalInstagramProfiles': result[2].data,
								'totalOwlooUsers': result[3].data,
							}
						});
					}
				}
			})

			.otherwise({redirectTo: '/'});

		$locationProvider.html5Mode(true);

	})

	// Activar para usar HTTPS
	// .config(["$compileProvider", function($compileProvider) {
	// 	$compileProvider.aHrefSanitizationWhitelist(/^\s*(data|https?):/)
	// }])

	.config(function ($translateProvider) {

		$translateProvider

			.translations('es', {
				//********** GLOBAL JAVASCRIPT TAG CODE **********
				'GLOBAL_TAG_CODE_ADDTHIS': '<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53ebf54859aaad3f"></script>',
				'GLOBAL_TAG_CODE_ANALYTICS': 'UA-18187325-45',
				'META_LANGUAGE': '<meta http-equiv="Content-Language" content="es">',
				'HTML_LANGUAGE': '<html lang="es">',
				'META_KEYWORDS': 'Facebook: statistics: Twitter: Instagram: social media marketing: social stats: Owloo: oulo: datos: ranking: estudio mercado: medir: análisis: crecimiento: analytics',
				'META_DESCRIPTION': 'Owloo ofrece el monitoreo y seguimiento gratis de Facebook: Twitter e Instagram: permitiendo el análisis y la comparación de las estadísticas e indicadores de las páginas: ciudades y países.',
				'META_TITLE': 'Estadísticas y Análisis de Facebook: Twitter e Instagram',
				'META_ROBOTS': '<meta name="robots" content="index,follow">',
				//********** META TAGS /facebook-stats/pages/hispanic/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content=“facebook: social media marketing: paginas: statistics: fb: stats: tool: analytic: Owloo: clasificación">',
				// 'META_DESCRIPTION': '<meta name="description" content=“Ranking de las páginas más populares en Facebook. Monitoreo y análisis comparativo gratuito en español.”>',
				// 'META_TITLE': '<title>Estadísticas de las páginas de Facebook - Owloo</title>',
				//********** META TAGS /facebook-stats/pages/world/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: statistics: paginas: pages: stats: Owloo: Owlo: ranking: ranking: medir: análisis: crecimiento: analytics">',
				// 'META_DESCRIPTION': '<meta name="description" content=" Ranking de las páginas más populares de Facebook en el mundo. Analiza y monitorea los datos de una página de Facebook.">',
				// 'META_TITLE': '<title>Páginas más populares en Facebook - Owloo</title>',
				//********** META TAGS /facebook-stats/pages/$1/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: statistics: $1: stats: engagement rate: Owloo: análisis: compare: analytic">',
				// 'META_DESCRIPTION': '<meta name="description" content="Analiza y compara el crecimiento de fans: la popularidad: el engagement y otros datos de la página de $1 en Facebook.">',
				// 'META_TITLE': '<title>Estadísticas de $1 en Facebook - Owloo</title>',
				//********** META TAGS /facebook-stats/pages/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: statistics: social media marketing: pages: Owloo: análisis: compare: más likes: analytic: competencia">',
				// 'META_DESCRIPTION': '<meta name="description" content="Herramienta de social media marketing para el monitoreo y seguimiento de Facebook. Ranking de las páginas más populares y análisis de la competencia.">',
				// 'META_TITLE': '<title>Monitoreo y seguimiento de Facebook - Owloo</title>',
				//********** META TAGS /facebook-stats/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: estudio mercado: ciudades: estados: Owloo: clasificacion: estudio mercado: statistics: estadisticas">',
				// 'META_DESCRIPTION': '<meta name="description" content="Te ayudamos a estudiar un mercado con los datos geográficos y demográficos de Facebook por países: regiones y ciudades.">',
				// 'META_TITLE': '<title>Datos y estadísticas de Facebook - Owloo</title>',
				//********** META TAGS /facebook-stats/world-ranking/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: paises: Owloo: datos: estudio mercado: world: statistics: tendencias: estadisticas">',
				// 'META_DESCRIPTION': '<meta name="description" content="Estadísiticas y ranking de Facebook por países. Datos avanzados para el estudio de un mercado y las tendencias locales.">',
				// 'META_TITLE': '<title>Estadísticas de Facebook por país - Owloo</title>',
				//********** META TAGS /facebook-stats/hispanic-ranking/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: paises: español: total ususarios: hispanic: Owloo: clasificacion: estudio mercado: statistics: estadisticas">',
				// 'META_DESCRIPTION': '<meta name="description" content="Estadísiticas de Facebook por países de habla hispana. Cantidad de usuarios: tendencias 2014 con datos actualizados diariamente.">',
				// 'META_TITLE': '<title>Estadísticas de Facebook en español - Owloo</title>',
				//********** META TAGS /facebook-stats/facebook-stats/$1/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Facebook: $1: uso: Owloo: penetracion: estudio mercado: estadisticas: datos">',
				// 'META_DESCRIPTION': '<meta name="description" content="Estudio de mercado sobre el uso de Facebook en $1. Puedes ver las estadísticas de crecimiento: número de usuarios: intereses: datos geográficos y demográficos de Facebook en $1.">',
				// 'META_TITLE': '<title>Estadísticas y datos de Facebook en $1 - Owloo</title>',
				//********** META TAGS /features/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Que es Owloo: about: social media analytic tools: Facebook: Twitter: Instagram: stats">',
				// 'META_DESCRIPTION': '<meta name="description" content="Owloo es una herramienta de social media marketing que permite estudiar un mercado: analizar Twitter y comparar las estadísticas de las páginas de Facebook e Instagram.">',
				// 'META_TITLE': '<title>Conoce Owloo</title>',
				//********** META TAGS /twitter-stats/hispanic/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Twitter: statistics: Klout: social media marketing: stats: Owloo: análisis: analizar: analytic: populares: competencia">',
				// 'META_DESCRIPTION': '<meta name="description" content="Herramienta para analizar gratis un perfil de Twitter y descargar las estadísticas de los twitteros hispanos más populares.">',
				// 'META_TITLE': '<title>Analizar Twitter con Owloo</title>',
				//********** META TAGS /twitter-stats/world/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Twitter: statistics: Klout: social media marketing: stats: Owloo: análisis: analizar: analytic: populares: competencia">',
				// 'META_DESCRIPTION': '<meta name="description" content="Analiza gratis un perfil de Twitter y descarga las estadísticas de los perfiles más populares del mundo.">',
				// 'META_TITLE': '<title>Más populares en Twitter - Owloo</title>',
				//********** META TAGS /twitter-stats/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Twitter: statistics: tweet: social media: monitoring: Klout: ff ratio: marketing: análisis: analizar: Twitter analytics: populares: competencia">',
				// 'META_DESCRIPTION': '<meta name="description" content="Análisis y seguimiento de Twitter que permite la comparación de las estadísticas de tu perfil y de la competencia. Klout score y FF ratio.">',
				// 'META_TITLE': '<title>Análisis y seguimiento de Twitter - Owloo</title>',
				//********** META TAGS /instagram-stats/ **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Instagram: statistics: stats: analytics: brands: estadisticas: datos: marcas: analizar">',
				// 'META_DESCRIPTION': '<meta name="description" content="Análisis de las principales cuentas de Instagram por categorías: analiza las estadísticas de las marcas más populares o de tu cuenta de Instagram gratis.">',
				// 'META_TITLE': '<title>Datos y estadísticas de Instagram - Owloo</title>',
				//********** META TAGS /instagram-stats/$1 **********
				// 'META_KEYWORDS': '<meta name="keywords" content="Instagram: statistics: stats: analytics: brands: estadisticas: datos: marcas: analizar">',
				// 'META_DESCRIPTION': '<meta name="description" content="Owloo te permite conocer las principales estadísticas: posición en el ranking mundial y analizar los datos de $ en Instagram.">',
				// 'META_TITLE': '<title>Estadísticas de $1 en Instagram - Owloo</title>',
				//********** MAIN MENU **********
				'OWLOO_MAIN_MENU_FACEBOOK': 'Facebook',
				'OWLOO_MAIN_MENU_TWITTER': 'Twitter',
				'OWLOO_MAIN_MENU_INSTAGRAM': 'Instagram',
				'OWLOO_MAIN_MENU_BLOG': 'Blog',
				//********** ADD PAGE/PROFILE TO OWLOO **********
				'OWLOO_ADD_TWITTER_ACCOUNT': 'Busca o agrega una cuenta de Twitter: https://... o @elnombre',
				'OWLOO_ADD_FACEBOOK_PROFILE': 'Busca o agrega una página de Facebook: https://facebook.com/...',
				'OWLOO_ADD_INSTAGRAM_PROFILE': 'Busca o agrega una cuenta de Instagram: https://Instagram.com/...',
				//********** PAY WITH YOUR SOCIAL NETWORKS **********
				'DOWNLOAD_STATS_SOCIALPAY_LOGIN_MESSAGE': 'Para descargar las estadísticas inicia sesión o regístrate primero',
				'DOWNLOAD_STATS_SOCIALPAY_ALERT': 'Por favor comparte esta página en una red social para descargar gratis las estadísticas completas con datos históricos de $1',
				'BUTTON_DOWNLOAD_STATS_SOCIALPAY': 'Descarga ahora',
				//********** OWLOO PUBLIC STATS **********
				'OWLOO_COUNTER_TWITTER_ACCOUNTS': '$1 CUENTAS DE TWITTER',
				'OWLOO_COUNTER_FACEBOOK_PAGES': '$1 PÁGINAS DE FACEBOOK',
				'OWLOO_COUNTER_INSTAGRAM_PAGES': '$1 CUENTAS DE INSTAGRAM',
				'OWLOO_COUNTER_USERS_REGISTERED': '$1 USUARIOS REGISTRADOS',
				'OWLOO_COUNTER_DATA_STORED': '$1 MB DE DATOS PROCESADOS',
				//************ SLOGAN *************
				'OWLOO_SUB_SLOGAN': 'La plataforma en $1 más completa para el análisis de las redes sociales',
				'OWLOO_MAIN_TITLE_1': 'Analiza Facebook: Twitter e Instagram. Ahora más potente que nunca.',
				//*********** FOOTER MENU ***********
				'FOOTER_MENU_SERVICE': 'Servicios',
				'FOOTER_MENU_SERVICE_FACEBOOK_COUNTRIES': 'Facebook por país y ciudad',
				'FOOTER_MENU_SERVICE_FACEBOOK_PAGES': 'Estadísticas de las páginas de Facebook',
				'FOOTER_MENU_SERVICE_TWITTER': 'Analiza una cuenta de Twitter',
				'FOOTER_MENU_SERVICE_INSTAGRAM': 'Estadísticas de las cuentas de Instagram',
				'FOOTER_MENU_ABOUT': 'Acerca de Owloo',
				'FOOTER_MENU_ABOUT_FEATURES': 'Conoce Owloo',
				'FOOTER_MENU_BLOG': 'Estudios y aprendizaje',
				'FOOTER_MENU_BLOG_HIGHLIGHT': 'Owloo Blog oficial',
				//*********** STATIC FOOTER ***********
				'FOOTER_SLOGAN': 'Owloo es una startup que ofrece el monitoreo y seguimiento de las redes sociales: <br/>permitiendo el análisis: y la comparación de estadísticas e indicadores de los medios sociales.',
				'FOOTER_COPYRIGHT': '&copy;2014 Owloo.',
				'FOOTER_TRADEMARK': 'Todos los derechos reservados.',
				'FOOTER_OTHER_TRADEMARKS': 'All other trademarks are the property of their respective owners.',
				'FOOTER_PRIVACY_POLICY': 'Políticas de privacidad',
				'FOOTER_TERMS': 'Términos de Uso',
				//************ ALERTS / ERRORS *****************
				'ERROR_404': '¡Vaya! Esta página no existe o en este momento no se encuentra disponible. Disculpa las molestias ocasionadas.',
				'ERROR_404_CONTINUE': 'Seguir navegando',
				'ERROR_500': 'Nuestros ingenieros están trabajando para resolver el problema. Inténtalo dentro de pocos minutos. Disculpa las molestias ocasionadas.',
				'ERROR_505': 'Nuestros ingenieros están trabajando para resolver el problema. Inténtalo dentro de pocos minutos. Disculpa las molestias ocasionadas.',
				'ERROR_SERVER_BUSY': 'Nuestros ingenieros están trabajando para resolver el problema. Inténtalo dentro de pocos minutos. Disculpa las molestias ocasionadas.',
				'ALERT_SIGNUP_1': 'Por favor completa los campos obligatorios.',
				'ALERT_SIGNUP_2': 'Otra cuenta en Owloo se encuentra registrada con esa dirección de correo electrónico.',
				'ALERT_SIGNIN_1': 'Por favor completa los campos correo electrónico y contraseña.',
				'ALERT_SIGNIN_2': 'El nombre de usuario o la contraseña que ingresaste es incorrecta.',
				'ALERT_SIGNIN_PASSWORD_RECOVERY': 'Este correo electrónico no se encuentra asociado a ninguna cuenta de Owloo.',
				//************ HIGHTLIGHTS TITLES *****************
				'MOST_GROWTH_FACEBOOK_PAGES_BY_COUNTRIES': 'Páginas de $1 con mayor crecimiento durante esta semana',
				'LAST_FACEBOOK_PAGES_ADDED_BY_CATEGORY': 'Últimas $1 de $2 agregadas',
				'LAST_FACEBOOK_PAGES_ADDED': 'Últimas páginas de Facebook agragadas a Owloo',
				'LAST_INSTAGRAM_PAGES_ADDED': 'Últimas páginas de Instagram agragadas a Owloo',
				'LAST_TWITTER_ACCOUNT_ADDED': 'Últimas cuentas de Twitter agragadas a Owloo',
				'MOST_TWITTER_ACCOUNT_GROWTH': 'Cuentas con mayor crecimiento en Twitter en $1',
				'MOST_FACEBOOK_PAGES_GROWTH': 'Páginas con mayor crecimiento en Facebook en $1',
				'MOST_FACEBOOK_COUNTRIES_GROWTH': 'Tasa de crecimiento en Facebook por países en $1',
				'RECOMMENDED_TWITTER_ACCOUNT': 'Cuentas de Twitter recomendadas',
				//************ SERVICES *****************
				//************ FACEBOOK COUNTRIES *****************
				'FACEBOOK_COUNTRIES_GREYBAR1': 'Resumen de las estadísticas de Facebook $1',
				'FACEBOOK_COUNTRIES_DESCRIPTION': 'Owloo te brinda datos demográficos y geográficos de Facebook en $1 y detalles estadísticos con los cuales puedes calcular el crecimiento: el uso de los celulares conectados a Facebook y los principales intereses de los usuarios en $2.',
				'FACEBOOK_COUNTRIES_STATS_RESUME': '$1 cuenta con $2 usuarios de los cuales el $3 son mujeres y $4 son hombres. Actualmente se encuentra en la posición $5 de los países con más usuarios registrados en Facebook.',
				'FACEBOOK_COUNTRIES_STATS_TABLE1': 'Audiencia total',
				'FACEBOOK_COUNTRIES_STATS_TABLE2': 'Ranking en el mundo',
				'FACEBOOK_COUNTRIES_STATS_TABLE3': 'Crecimiento',
				'FACEBOOK_COUNTRIES_STATS_TABLE4': 'Idioma principal',
				'FACEBOOK_COUNTRIES_STATS_TABLE5': 'Porcentaje de mujeres',
				'FACEBOOK_COUNTRIES_STATS_TABLE6': 'Porcentaje de hombres',
				'FACEBOOK_COUNTRIES_GREYBAR2': 'Crecimiento de usuarios en Facebook $1',
				'FACEBOOK_COUNTRIES_STATS_LOST_6MONTHS': 'Pérdida en 6 meses',
				'FACEBOOK_COUNTRIES_STATS_GROWTH_1MONTH': 'Último mes',
				'FACEBOOK_COUNTRIES_STATS_GROWTH_2MONTHS': '2 meses',
				'FACEBOOK_COUNTRIES_STATS_GROWTH_3MONTHS': '3 meses',
				'FACEBOOK_COUNTRIES_STATS_GROWTH_6MONTHS': '6 meses',
				'FACEBOOK_COUNTRIES_GREYBAR3': 'Estadísticas geográficas de Facebook $1',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_GEO1': 'Ciudad',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_GEO2': 'Audiencia',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_GEO3': 'Porcentaje',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_GEOLOCK_CITY': 'Ver estadísticas detalladas de las ciudades',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_GEORESUME': 'Con $1 de usuarios $2 es la ciudad de $3 con más cantidad de usuarios registrados en Facebook: seguido $4 con $5 usuarios y en tercer lugar con $6 se encuentra $7.',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_GEO1': 'Región/Estado',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_GEO2': 'Audiencia',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_GEO3': 'Porcentaje',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_GEOLOCK_REGION': 'Ver estadísticas detalladas por región/estado',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_GEORESUME': 'Con $1 de usuarios $2 es la región de $3 con más cantidad de usuarios registrados en Facebook: seguido por $4 con $5 y en tercer lugar $6 con $7 de usuarios.',
				'FACEBOOK_COUNTRIES_GREYBAR4': 'Estadísticas demográfica de Facebook $1',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_DEMOGRAPHIC1': 'Edades',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_DEMOGRAPHIC2': 'Audiencia',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_DEMOGRAPHIC3': 'Porcentaje',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_DEMOGRAPHIC_HIGHLIGHT1': 'Hombres',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_DEMOGRAPHIC_HIGHLIGHT2': 'Mujeres',
				'FACEBOOK_COUNTRIES_STATS_TABLELEFT_DEMOGRAPHICRESUME': 'La edad promedio del usuario de Facebook $1 es de $2 - $3: equivalente al $4% de la cantidad total. Las Mujeres ocupan mayormente de $5 años y los Hombres son más de $6 - $7 años de edad.',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_DEMOGRAPHIC1': 'Idiomas',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_DEMOGRAPHIC2': 'Audiencia',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_DEMOGRAPHIC3': 'Porcentaje',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_DEMOGRAPHICLOCK': 'Ver más idiomas hablados en Facebook $1',
				'FACEBOOK_COUNTRIES_STATS_TABLERIGHT_DEMOGRAPHICRESUME': 'En $1 el $2% de los usuarios en Facebook definen como su idioma principal al $3: en segundo lugar se encuentra el $4 con el $5% de usuarios: posteriormente se encuentra el $6 con $7% de usuarios: el $8% habla $9 y finalmente el $10% utiliza el $11.',
				'FACEBOOK_COUNTRIES_STATS_DEMOGRAPHIC_TRENDUPAGES': 'Edades',
				'FACEBOOK_COUNTRIES_STATS_DEMOGRAPHIC_TRENDUPLANGUAGES': 'Idiomas',
				'FACEBOOK_COUNTRIES_GREYBAR5': 'Situación sentimental de los usuarios de Facebook $1',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP1': 'Casados',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP2': 'Solteros',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP3': 'Comprometidos',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP4': 'Relación',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP5': 'Sin especificar',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP6': 'Pareja',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP7': 'Relación libre',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP8': 'Complicada',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP9': 'Separado',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP10': 'Divorciado',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP11': 'Viudo',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIP12': '',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIPRESUME': 'En $1 existen $2 usuarios que señalan su situación sentimental en Facebook: de los cuales $3 afirman estar $4: $5 mencionan estar $6: $7 indican estar $8 y $9 especifican tener una $10 en este momento.',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIPTRENDUP1': 'Hombres',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIPTRENDUP2': 'Mujeres',
				'FACEBOOK_COUNTRIES_STATS_RELATIONSHIPTRENDUP3': 'Global',
				'FACEBOOK_COUNTRIES_GREYBAR6': 'Intereses y comporatmientos de los usuarios de Facebook en $1',
				'FACEBOOK_COUNTRIES_INTERESTS_TABLE_TITLE1': 'Intereses',
				'FACEBOOK_COUNTRIES_INTERESTS_TABLE_TITLE2': 'Comportamientos',
				'FACEBOOK_COUNTRIES_INTERESTS_TABLE_STATS_1': 'Crecimiento',
				'FACEBOOK_COUNTRIES_INTERESTS_TABLE_STATS_2': 'Trend Up',
				'FACEBOOK_COUNTRIES_INTERESTS_TABLE_STATS_3': 'Audiencia',
				'FACEBOOK_COUNTRIES_INTERESTS_STATS_SUBCATEGORY': 'Intereses afines',
				'FACEBOOK_COUNTRIES_INTERESTS_STATS_AGES': 'Edades de los usuarios',
				'FACEBOOK_COUNTRIES_INTERESTS_STATS_CITIES': 'Ciudades',
				'FACEBOOK_COUNTRIES_GREYBAR7': 'Dispositivos móviles conectados a Facebook en $1',
				'FACEBOOK_COUNTRIES_MOBILE_STATS_SUBTITLE': 'Estadísticas detalladas de usuarios $1',
				'FACEBOOK_COUNTRIES_MOBILE_STATS_1': 'Dispositivos',
				'FACEBOOK_COUNTRIES_MOBILE_STATS_2': 'Edades de los usuarios',
				'FACEBOOK_COUNTRIES_MOBILE_STATS_3': 'Ciudades',
				//************ FACEBOOK PAGE *****************
				'FACEBOOK_PAGE_GRAYBAR1': 'Resúmen de las estadísticas',
				'FACEBOOK_PAGE_STATS_TABLE1': 'Cantidad total de fans',
				'FACEBOOK_PAGE_STATS_TABLE2': 'Personas hablando de esto',
				'FACEBOOK_PAGE_STATS_TABLE3': 'Página verificada',
				'FACEBOOK_PAGE_STATS_TABLE4': 'En Owloo desde',
				'FACEBOOK_PAGE_STATS_TABLE5': 'Localización',
				'FACEBOOK_PAGE_STATS_TABLE5-2': 'Popular en', //new
				'FACEBOOK_PAGE_STATS_TABLE6': 'Ranking en $1',
				'FACEBOOK_PAGE_STATS_TABLE_RESUME1': '$1 cuenta con $2 fans en Facebook y actualmente hay $3 personas hablando de esto en la red social.',
				'FACEBOOK_PAGE_STATS_TABLE_RESUME2': 'El país con más fans de $1 es $2: además se posiciona en el puesto $3 dentro del ranking de páginas más populares de $4',
				'FACEBOOK_PAGE_STATS_TABLE_RESUME3': 'El país con más fans de $1 es $2: y se posiciona en el puesto $3 dentro del ranking de páginas más populares de $4', //new
				'FACEBOOK_PAGE_GRAYBAR2': 'Crecimiento de fans durante los últimos $1 días',
				'FACEBOOK_PAGE_STATS_GROWTH_LOST_1MONTH': 'Pérdida en 30 días',
				'FACEBOOK_PAGE_STATS_GROWTH_24HOURS': '24 horas',
				'FACEBOOK_PAGE_STATS_GROWTH_7DAYS': '7 días',
				'FACEBOOK_PAGE_STATS_GROWTH_14DAYS': '14 días',
				'FACEBOOK_PAGE_STATS_GROWTH_1MONTH': 'Último mes',
				'FACEBOOK_PAGE_STATS_FPER_1WEEK': 'FPER 7 días',
				'FACEBOOK_PAGE_STATS_FPER_1MONTH': 'FPER último mes',
				'FACEBOOK_PAGE_GRAYBAR3': 'Engagement rate de las publicaciones',
				'FACEBOOK_PAGE_STATS_POST_ENGAGEMENT_RATE_RESUME': 'Facebook post engagement rate. Análisis de participación de personas con publicaciones hechas por $1 en Facebook: en la actualidad genera un engagement de $2%.',
				'FACEBOOK_PAGE_GRAYBAR4': 'Facebook market share',
				'FACEBOOK_PAGE_STATS_FB_MARKET_SHARE_1': 'Localizada / Popular en',
				'FACEBOOK_PAGE_STATS_FB_MARKET_SHARE_2': 'Market share',
				'FACEBOOK_PAGE_STATS_FB_MARKET_SHARE_RESUME': 'Owloo calcula la participación del mercado: en base a la cantidad de fans de la página y los usuarios registrados en Facebook dentro del país en donde se localiza o es popular dicha página.',
				'FACEBOOK_PAGE_GRAYBAR5': 'Segmentación de fans por países y ciudades',
				'FACEBOOK_PAGE_STATS_COUNTRY_FANS_1': 'País',
				'FACEBOOK_PAGE_STATS_COUNTRY_FANS_2': 'Fans',
				'FACEBOOK_PAGE_STATS_COUNTRY_FANS_3': 'Porcentaje',
				'FACEBOOK_PAGE_STATS_COUNTRY_FANS_EXPAND': 'Expandir las estadísticas',
				'FACEBOOK_PAGE_STATS_COUNTRY_FANS_RESUME1': 'Con $1 $2 es el país con mayor cantidad de fans que siguen a $3 en Facebook: seguido por $4 con $5 y $6 con $7. ',
				'FACEBOOK_PAGE_STATS_COUNTRY_FANS_RESUME2': '',
				'FACEBOOK_PAGE_GRAYBAR6': 'Crecimiento de fans en',
				'FACEBOOK_PAGE_GRAYBAR6-2': 'durante los últimos 30 días',
				'FACEBOOK_PAGE_STATS_LOCAL_GROWTH_24HOURS': '24 horas',
				'FACEBOOK_PAGE_STATS_LOCAL_GROWTH_7DAYS': '7 días',
				'FACEBOOK_PAGE_STATS_LOCAL_GROWTH_14DAYS': '14 días',
				'FACEBOOK_PAGE_STATS_LOCAL_GROWTH_1MONTH': 'Último mes',
				//************ FACEBOOK PAGES CATEGORIES *****************
				'FACEBOOK_PAGES_CATEGORY_1': 'Todas',
				'FACEBOOK_PAGES_CATEGORY_2': 'Internet',
				'FACEBOOK_PAGES_CATEGORY_3': 'Comunidades',
				'FACEBOOK_PAGES_CATEGORY_4': 'Deportes',
				'FACEBOOK_PAGES_CATEGORY_5': 'Música',
				'FACEBOOK_PAGES_CATEGORY_6': 'Televisión',
				'FACEBOOK_PAGES_CATEGORY_7': 'Personalidades',
				'FACEBOOK_PAGES_CATEGORY_8': 'Medios',
				'FACEBOOK_PAGES_CATEGORY_9': 'Política',
				'FACEBOOK_PAGES_CATEGORY_10': 'Negocios locales',
				'FACEBOOK_PAGES_CATEGORY_11': 'Empresas',
				'FACEBOOK_PAGES_CATEGORY_12': 'Marcas',
				//************ TWITTER PROFILE STATS *****************
				'TWITTER_ACCOUNT_H1': 'Datos y estadísticas de $1 en Twitter',
				'TWITTER_ACCOUNT_GREYBAR1': 'Resumen de las estadísticas',
				'TWITTER_ACCOUNT_STATS_TABLE1': 'Cantidad total de seguidores',
				'TWITTER_ACCOUNT_STATS_TABLE2': 'Está siguiendo a',
				'TWITTER_ACCOUNT_STATS_TABLE3': 'Total de tweets',
				'TWITTER_ACCOUNT_STATS_TABLE4': 'En Owloo desde',
				'TWITTER_ACCOUNT_STATS_TABLE5': 'En Twitter desde',
				'TWITTER_ACCOUNT_STATS_TABLE6': 'Localización',
				'TWITTER_ACCOUNT_STATS_RESUME1': '$1 cuenta con $2 seguidores en todo el mundo y con $3 tweets publicados. Actualmente está siguiendo a $4 y su Klout score es de $5.',
				'TWITTER_ACCOUNT_GREYBAR2': 'Crecimiento de seguidores de $1',
				'TWITTER_ACCOUNT_STATS_GROWTH_LOST_1MONT': 'Pérdida en 30 días',
				'TWITTER_ACCOUNT_STATS_GROWTH_24HOURS': '24 horas',
				'TWITTER_ACCOUNT_STATS_GROWTH_7DAYS': '7 días',
				'TWITTER_ACCOUNT_STATS_GROWTH_14DAYS': '14 días',
				'TWITTER_ACCOUNT_STATS_GROWTH_1MONTH': 'Último mes',
				'TWITTER_ACCOUNT_GREYBAR3': 'Análisis de hashtags y menciones de $1',
				'TWITTER_ACCOUNT_STATS_MOST_MENTIONED': 'Perfiles más mencionados',
				'TWITTER_ACCOUNT_STATS_MOST_HASHTAGS': 'Hashtags más utilizados',
				'TWITTER_ACCOUNT_STATS_MOST_RESUME1': 'Se muestran los hashtags más utilizados y los perfiles más mencionados por $1 en Twitter desde que está presente en Owloo.',
				'TWITTER_ACCOUNT_GREYBAR4': 'Medición de la influencia en Twitter',
				'TWITTER_ACCOUNT_STATS_KLOUT': 'Klout',
				'TWITTER_ACCOUNT_STATS_KLOUT_ABOUT': 'Klout mide el grado de influencia de una persona o una marca en Twitter. Para determinar el Klout score se analizan más de 400 parámetros distintos y se asigna una puntuación entre 1 y 100 a los usuarios. El promedio de los usuarios es de 40 y se considera como un influenciador.',
				'TWITTER_ACCOUNT_GREYBAR5': 'FF Ratio de $1 en Twitter',
				'TWITTER_ACCOUNT_STATS_RATIO': 'FF Ratio',
				'TWITTER_ACCOUNT_STATS_RATIO1': 'Cantidad de seguidores',
				'TWITTER_ACCOUNT_STATS_RATIO2': 'Está siguiendo a',
				'TWITTER_ACCOUNT_STATS_RATIO_ABOUT': 'El ratio es una métrica que mide la forma en la que nos estamos comunicando en Twitter. Se califica un ratio positivo entre 0.90 y 1.50 que significa que escuchas y eres escuchado.',
				//************ GREY BAR FOR CSV DONWLOAD *****************
				'OWLOO_DOWNLOAD_FREE_TWITTER_CSV': 'Puedes descargar gratis el reporte completo en CSV con los datos históricos de $.',
				//************ SIGNUP PAGE *****************
				'OWLOO_SIGNUP_BLUEBAR': 'Crea una cuenta gratuita en Owloo',
				'OWLOO_SIGNUP_H1': 'Crea tu cuenta Owloo',
				'OWLOO_SIGNUP_FIELD_EMAIL': 'Correo electrónico',
				'OWLOO_SIGNUP_FIELD_PASSWORD': 'Contraseña',
				'OWLOO_SIGNUP_FIELD_PASSWORD_CONFIRM': 'Repite la contraseña',
				'OWLOO_SIGNUP_REVERSE': '¿Ya tienes cuenta? Inicia sesión',
				//************ SIGNIN PAGE *****************
				'OWLOO_SIGNIN_BLUEBAR': 'Inicia sesión en Owloo',
				'OWLOO_SIGNIN_H1': 'Iniciar sesión',
				'OWLOO_SIGNIN_FIELD_EMAIL': 'Correo electrónico',
				'OWLOO_SIGNIN_FIELD_PASSWORD': 'Contraseña',
				'OWLOO_SIGNIN_PASSWORD_REMEMBER': '¿Olvidaste tu contraseña?',
				'OWLOO_SIGNIN_REVERSE': '¿Aún no estás registrado?',
				//************ SOCIAL PROVIDER SIGNUP / SIGNIN PAGES *****************
				'OWLOO_SOCIAL_PROVIDER_FB': 'Facebook',
				'OWLOO_SOCIAL_PROVIDER_TW': 'Twitter',
				'OWLOO_SOCIAL_PROVIDER_LI': 'LinkedIn',
				'OWLOO_SOCIAL_PROVIDER_GO': 'Google',
				'OWLOO_SOCIAL_PROVIDER_IN': 'Instagram',
				//************ PASSWORD RECOVERY PAGE *****************
				'OWLOO_PASSWORD_RECOVERY_1': 'Restablece tu contraseña',
				'OWLOO_PASSWORD_RECOVERY_2': 'Ingresa tu correo electrónico en el campo para recibir el código de autorización necesario para restablecer tu contraseña.',
				//************ USER ACCOUNT *****************
				'OWLOO_USER_ACCOUNT_DROPDOWN_OPTION_1': 'Mi cuenta',
				'OWLOO_USER_ACCOUNT_DROPDOWN_OPTION_2': 'Cerrar sesión',
				'OWLOO_USER_ACCOUNT_H1': 'Configuración de la cuenta',
				'OWLOO_USER_ACCOUNT_PERSONAL_TAB': 'Datos personales',
				'OWLOO_USER_ACCOUNT_PERSONAL_1': 'Nombre completo',
				'OWLOO_USER_ACCOUNT_PERSONAL_2': 'País',
				'OWLOO_USER_ACCOUNT_PERSONAL_3': 'Correo electrónico principal',
				'OWLOO_USER_ACCOUNT_PERSONAL_4': 'Tipo de cuenta',
				'OWLOO_USER_ACCOUNT_PERSONAL_5': 'Actualizar contraseña',
				'OWLOO_USER_ACCOUNT_PERSONAL_6': 'Para actualizar la contraseña debe proveer primero la contraseña actual',
				'OWLOO_USER_ACCOUNT_PERSONAL_6_FIELD_1': 'Contraseña actual',
				'OWLOO_USER_ACCOUNT_PERSONAL_6_FIELD_2': 'Nueva contraseña',
				'OWLOO_USER_ACCOUNT_PERSONAL_6_FIELD_3': 'Confirmar tu nueva contraseña',
				'OWLOO_USER_ACCOUNT_PERSONAL_7': 'Vicunla la cuenta de Owloo con tus redes sociales',
				'OWLOO_USER_ACCOUNT_PERSONAL_8': 'Te permite un acceso a Owloo más rápido sin necesidad de introducir tu contraseña',
				'OWLOO_USER_ACCOUNT_PERSONAL_9': 'Conectar',
				'OWLOO_USER_ACCOUNT_PERSONAL_10': 'Desconectar',
				'OWLOO_USER_ACCOUNT_SERVICES_TAB': 'Servicios activos',
				'OWLOO_USER_ACCOUNT_SERVICES_1': 'Estado:',
				'OWLOO_USER_ACCOUNT_SERVICES_2': 'Acciones:',
				'OWLOO_USER_ACCOUNT_SERVICES_3': 'Darse de baja:',
				'OWLOO_USER_ACCOUNT_SERVICES_4': 'Cancelar servicio:',
				'OWLOO_USER_ACCOUNT_BILLING_TAB': 'Pago y facturación',
				'OWLOO_USER_ACCOUNT_BILLING_1': 'Fecha',
				'OWLOO_USER_ACCOUNT_BILLING_2': 'Descripción',
				'OWLOO_USER_ACCOUNT_BILLING_3': 'Pago realizado',
				'OWLOO_USER_ACCOUNT_BILLING_4': 'Factura',
				'OWLOO_USER_ACCOUNT_BILLING_5': 'Descargar',
				//************ GLOBAL SITE TERMS *****************
				'TREND_UP': 'Trend Up',
				'AVERAGE': 'Promedio',
				'CLOSE_STATS': 'Cerrar estadísticas',
				'GENRES_MENS': 'Hombres',
				'GENRES_WOMENS': 'Mujeres',
				'GROWTH': 'Crecimiento',
				'USERS': 'Ususarios',
				'CITY': 'Ciudad',
				'CITIES': 'Ciudades',
				'COUNTRY': 'País',
				'COUNTRIES': 'Países',
				'FACEBOOK_PAGE_CATEGORY': 'Categoría de la página',
				'COUNTRY_SELECT': 'Seleccionar un país',
				'OWLOO_CHOICE_SIGNUP_SIGNIN': 'o',
				'ITALIAN': 'Italiano',
				'SPANISH': 'Español',
				'ENGLISH': 'English',
				'EUROPE': 'Europa',
				'HISPANIC': 'Hispanos',
				'HISPANIC_AMERICA': 'Hispanoamérica',
				'AGES': 'Edades',
				'AUDIENCE': 'Audiencia',
				'PERCENTAGE': 'Porcentaje',
				'HASHTAGS': 'Hashtags',
				'MENTIONS': 'Menciones',
				'DOWNLOAD': 'Descargar',
				'RANKING': 'Ranking',
				//********** GLOBAL BUTTONS **********
				'BUTTON_SIGNUP': 'Regístrate hoy - GRATIS',
				'BUTTON_SIGNIN': 'Iniciar sesión',
				'BUTTON_SEND': 'Enviar',
				'BUTTON_CREATE_ACCOUNT': 'Crear cuenta',
				'BUTTON_PROFILE_ANALYZE': 'Analizar perfil',
				'BUTTON_PAGE_ANALYZE': 'Analizar página',
				'BUTTON_CONFIRM': 'Confirmar',
				'BUTTON_DOWNLOAD_STATS': 'Descargar estadísticas',
				'BUTTON_COOKIES_DIRECTIVE': 'Estoy de acuerdo',
				//************ MONTHS ***********
				'JAN': 'Enero',
				'FEB': 'Febrero',
				'MAR': 'Marzo',
				'APR': 'Abril',
				'MAY': 'Mayo',
				'JUN': 'Junio',
				'JUL': 'Julio',
				'AUG': 'Agosto',
				'SEP': 'Septiembre',
				'OCT': 'Octubre',
				'NOV': 'Noviembre',
				'DEC': 'Diciembre',
				//********** NUEVOS AGREGADOS SEPTIEMBRE 5  **********
				//************ OWLOO FEATURES *****************
				'OWLOO_FEATURES_BLUE_BAR': 'Conoce Owloo y sus herramientas de social media',
				'OWLOO_FEATURES_H1_TITLE': 'Social Media Analytics. All in-one.',
				'OWLOO_FEATURES_H3_DESC': 'Hemos creado excelentes herramientas de social media analytics que permitien el análisis y la comparación de las datos y estadísticas de Facebook: Twitter e Instagram.',
				'OWLOO_FEATURES_FEATURE1_TITLE': 'Tendencias del mercado',
				'OWLOO_FEATURES_FEATURE1_DESC': 'Es una necesidad conocer y analizar las tendencias de un mercado. Owloo te permite analizarlas y crear tu estudio de mercado fácilmente.',
				'OWLOO_FEATURES_FEATURE2_TITLE': 'Facebook por países',
				'OWLOO_FEATURES_FEATURE2_DESC': 'Owloo te ayuda a conocer los datos demográficos y geográficos de Facebook en un determinado país: región o ciudad.',
				'OWLOO_FEATURES_FEATURE3_TITLE': 'Páginas de Facebook',
				'OWLOO_FEATURES_FEATURE3_DESC': 'Te ofrecemos las estadísticas de las páginas de Facebook: pudiendo monitorear el crecimiento: el PTA y la popularidad por país.',
				'OWLOO_FEATURES_FEATURE4_TITLE': 'Analizar Twitter',
				'OWLOO_FEATURES_FEATURE4_DESC': 'Analiza datos estadísticos de Twitter: los #hashtags: las cuentas populares: los influenciadores: el Klout score y mucho más.',
				'OWLOO_FEATURES_FEATURE5_TITLE': 'Intereses y comportamientos',
				'OWLOO_FEATURES_FEATURE5_DESC': 'Te ayudamos a conocer un público específico en un determinado país en base a sus intereses: gustos y comportamientos.',
				'OWLOO_FEATURES_FEATURE6_TITLE': 'Monitorea la competencia',
				'OWLOO_FEATURES_FEATURE6_DESC': 'Descubre las estadísticas y analizar los datos de las marcas y de tu competencia en Facebook: Twitter e Instagram.',
				'OWLOO_FEATURES_FEATURE7_TITLE': 'Herramientas exclusivas',
				'OWLOO_FEATURES_FEATURE7_DESC': 'Owloo te ofrece herramientas avanzadas de social media marketing para mejorar el trabajo de análisis y monitoreo de las redes sociales.',
				'OWLOO_FEATURES_FEATURE8_TITLE': 'Instagram stats',
				'OWLOO_FEATURES_FEATURE8_DESC': 'Estamos trabajando para ofrecerte las estadísticas de Instagram. Mientras: analiza el crecimiento de tu marca y el de la competencia.',
				'OWLOO_FEATURES_LARGE_DESC': '',
				//************ EXPLAIN ICON POPUP *****************
				'EXPLAIN_POPUP_FACEBOOK_COUNTRIES_1': 'El ranking de Facebook por países se genera midiendo: en cada país: la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en un determinado país. Puedes acceder a las estadísticas completas de cada país dando clic al nombre del país.',
				'EXPLAIN_POPUP_FACEBOOK_COUNTRIES_2': 'El ranking de Facebook por países hispanos se genera midiendo: por cada país de habla en español: la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en un determinado país hispano. Puedes acceder a las estadísticas completas de cada país dando a través del enlace.',
				'EXPLAIN_POPUP_FACEBOOK_COUNTRIES_3': 'El ranking de Facebook por ciudades se genera midiendo la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses en cada ciudad del mundo. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en una determinada ciudad: los intereses y comportamientos más populares y el segmento de edades. Puedes ver las estadísticas detalladas dando clic encima del nombre de la ciudad.',
				'EXPLAIN_POPUP_FACEBOOK_COUNTRIES_4': 'El ranking de Facebook por regiones se genera midiendo la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses sumando todos los usuarios provenientes de una región específoca. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en una determinada región: los intereses y comportamientos más populares y el segmento de edades. Puedes ver las estadísticas de Facebook por regiones dando clic encima del nombre de la región.',
				'EXPLAIN_POPUP_FACEBOOK_COUNTRIES_5': 'En este ranking se clasifican los continentes por cantidad de usuarios registrados en Facebook durante los últimos 3 meses. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en un continente específico. Actualmente la cantidad total de usuarios de Facebook en todo el mundo es de $1.',
				'EXPLAIN_POPUP_FACEBOOK_PAGES_1': 'El ranking global de las páginas de Facebook se genera midiendo el crecimiento diario de cada una de las páginas monitoreadas por Owloo. Dando clic en el nombre de la página: puedes descubrir las estadísticas detalladas de cada una de ellas: analizando los KPIs más importantes de tu página o de la competencia.',
				'EXPLAIN_POPUP_FACEBOOK_PAGES_2': 'Facebook clasifica las páginas por categorías al momento de que son creadas. Con Owloo puedes descubrir cuáles son las páginas más populares y con más crecimiento según estas 11 categorías principales. Ingresando en un determinado país: puedes segmentar las páginas en una categoría específica y en un país específico. En cada categoría y país mostramos el ranking semanal de las páginas más populares: las páginas con el mejor market share: mejor engagement y mayor crecimiento.',
				'EXPLAIN_POPUP_TWITTER_1': 'El ranking de las cuentas más populares de Twitter de todo el mundo se genera midiendo diariamente la cantidad de seguidores que aumentaron o disminuyeron entre todos los perfiles de Twitter monitoreadas por Owloo. Si no encuentra un determinado perfil: puedes añadirlo a través de campo presente al final de la página. Así mismo podemos analizar las estadísticas completa de la cuenta dando clic al nombre.',
				'EXPLAIN_POPUP_TWITTER_2': 'El ranking de las cuentas más populares de Twitter en español se genera midiendo diariamente la cantidad de seguidores que aumentaron o disminuyeron entre todas las cuentas de Twitter monitoreadas por Owloo. Si no encuentra un determinado perfil: puedes añadirlo a través de campo presente al final de la página. Así mismo podemos analizar las estadísticas completa de la cuenta dando clic al nombre.',
				'EXPLAIN_POPUP_INSTAGRAM_1': 'El ranking de las marcas en Instagram se genera midiendo el crecimiento diario de los seguidores de todas las cuentas existentes monitoreadas por Owloo. Dando clic en el nombre de la marca: puedes descubrir las estadísticas detalladas como las menciones: posts y los hashtags. Si no encuentra una determinada marca en la lista: puedes añadirla a través de campo presente al final de la página.',
				//************ LANDING PAGE FACEBOOK *****************
				'LANDING_PAGE_FACEBOOK_BLUE_BAR': 'Análisis y estadísticas de Facebook',
				'LANDING_PAGE_FACEBOOK_H1_TITLE': 'Herramienta de monitoreo y seguimiento de las páginas de Facebook© y análisis de las estadísticas por países: regiones y ciudades',
				'LANDING_PAGE_FACEBOOK_VIDEO1_TITLE': 'Facebook Analytics para todos',
				'LANDING_PAGE_FACEBOOK_VIDEO1_DESC': 'Owloo brinda estadísticas y datos valiosos para quiénes quieran realizar un estudio de mercado y analizar los datos estadísticos de una marca en Facebook. Descubre sus características principales y comienza a maximizar tu trabajo de social media marketing e análisis.',
				'LANDING_PAGE_FACEBOOK_VIDEO2_TITLE': 'Mejora el marketing en Facebook',
				'LANDING_PAGE_FACEBOOK_VIDEO2_DESC': 'Optimizar el trabajo de marketing en Facebook es de fundamental importancia. Owloo te ayuda a mejorar tus esfuerzos en Facebook: aumentar la efectividad de tu página y monitorear la competencia. En este video te explicamos en poco más de 1 minuto porqué y cómo tienes que usar Owloo para aprovechar su máxima potencia.',
				'LANDING_PAGE_FACEBOOK_HIGHTLIGHT_CURSIVETEXT_ORANGE': 'Descubre ahora las estadísticas de las páginas más populares de Facebook en tu país.',
				'LANDING_PAGE_FACEBOOK_EXPLAIN_DESC': 'Owloo es un software de monitoreo y seguimiento creado para ayudar el análisis y la comparación de las estadísticas de las redes sociales con el fin de optimizar los esfuerzos en marketing e investigaciones de mercados.',
				'LANDING_PAGE_FACEBOOK_H2_TITLE_1': 'Analiza intereses y tendencias',
				'LANDING_PAGE_FACEBOOK_H2_TITLE_2': 'Análisis y estudio de mercados',
				'LANDING_PAGE_FACEBOOK_H2_TITLE_3': 'Estadísticas de las páginas de Facebook',
				'LANDING_PAGE_FACEBOOK_H4_DESC_1': 'Descubre el uso de los dispositivos móviles en un determinado país o ciudad. Analiza los intereses de tu público: descubre los comportamientos y tendencias.',
				'LANDING_PAGE_FACEBOOK_H4_DESC_2': 'Owloo te ayuda a conocer los datos demográficos y geográficos de un determinado país: región o ciudad. Estudio de mercado más fácil que nunca.',
				'LANDING_PAGE_FACEBOOK_H4_DESC_3': 'Analiza las estadísticas de las marcas y páginas más populares en Facebok por categorías. Monitorea el crecimiento: el engagement y la popularidad de tu competencia.',
				'LANDING_PAGE_FACEBOOK_ALL_COUNTRY': 'Ver todos los países',
				'LANDING_PAGE_FACEBOOK_GLOBAL_STATS_1': 'de usuarios en total',
				'LANDING_PAGE_FACEBOOK_GLOBAL_STATS_2': 'Promedio',
				'LANDING_PAGE_FACEBOOK_GLOBAL_STATS_3': 'Hombres',
				'LANDING_PAGE_FACEBOOK_GLOBAL_STATS_4': 'Mujeres',
				//************ LANDING PAGE INSTAGRAM *****************
				'LANDING_PAGE_INSTAGRAM_BLUE_BAR': 'Análisis y estadísticas de Instagram',
				'LANDING_PAGE_INSTAGRAM_H1_TITLE': 'Analiza las estadísticas de las páginas y marcas más populares en Instagram. Comienza a analizar la competencia y optimizar tu presencia.',
				'LANDING_PAGE_INSTAGRAM_VIDEO1_TITLE': 'Marketing en Instagram. Excepcional.',
				'LANDING_PAGE_INSTAGRAM_VIDEO1_DESC': 'Analizar las estadísticas de Instagram con Owloo te ayuda a conocer la audiencia y mejorar tu presencia. Además descubre el ranking de las marcas más populares en Instagram: monitorea y analiza tu competencia revisando sus estadísticas principales.',
				'LANDING_PAGE_INSTAGRAM_HIGHTLIGHT_CURSIVETEXT_517fa4': 'Descubre ahora las estadísticas de las páginas de Instagram más populares en tu país.',
				'LANDING_PAGE_INSTAGRAMK_H2_TITLE_1': 'Hashtags y alcance de tus publicaciones',
				'LANDING_PAGE_INSTAGRAM_H2_TITLE_2': 'Crecimiento de seguidores',
				'LANDING_PAGE_INSTAGRAM_H2_TITLE_3': 'Analizar la competencia',
				'LANDING_PAGE_INSTAGRAM_H4_DESC_1': 'Owloo te ofrece un análisis detallado para medir el alcance de tus publicaciones y analizar el impacto de tus hashtags en Instagram.',
				'LANDING_PAGE_INSTAGRAM_H4_DESC_2': 'Mantén bajo contro el crecimiento de tus seguidores en Instagram: analiza otros datos esenciales como likes y comentarios durante un determinado período.',
				'LANDING_PAGE_INSTAGRAM_H4_DESC_3': 'Descubre las páginas de tu competencia y las marcas más populares en Instagram. Analiza el crecimiento diario de tus competidores: las publicaciones y sus seguidores.',
				'LANDING_PAGE_INSTAGRAM_MOST_GROWTH': 'Mayor crecimiento semanal',
				'LANDING_PAGE_INSTAGRAM_MOST_LIKES_POSTS': 'Publicaciones y engagement'
			})

			.preferredLanguage('es');

	})

	.run(function ($rootScope) {

		$rootScope.urlRoot = 'http://www.owloo.com/dev/3.1/';
		$rootScope.imagesFolder = 'http://www.owloo.com/dev/3.1/app/img/';

	});

});