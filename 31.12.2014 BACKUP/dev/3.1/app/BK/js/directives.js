define(['angular'], function (angular) {

	'use strict';

	return angular.module('owloo.directives', [])

	.directive('owlooFooter', function() {
		return {
			restrict: 'E',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/footer.html'
		};
	})

	.directive('widgetBoxUpright', function() {
		return {
			restrict: 'E',
			scope: {
				picture: '@',
				class: '@',
				grownumber: '@',
				growpercent: '@',
				link: '@',
				name: '@'
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/box-upright.html'
		};
	})

	.controller('widgetBoxHorizontalController', ['$scope', '$rootScope', function ($scope, $rootScope) {
		var icon_explode = $scope.icon.split("_");
		$scope.imagesFolder = $rootScope.imagesFolder;
		$scope.icon_text = icon_explode[0];
		if($scope.icon_text == 'flag'){
			$scope.code = icon_explode[1];
		}else{
			$scope.link = '';
		}
	}])

	.directive('widgetBoxHorizontal', function() {
		return {
			restrict: 'E',
			scope: {
				icon: '@',
				class: '@',
				value: '@',
				percent: '@',
				link: '@',
				footer: '@'
			},
			controller: 'widgetBoxHorizontalController',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/box-horizontal.html'
		};
	})

	.controller('LineChartController', ['$scope', function ($scope, $element, $window, $filter) {

		var margin = {top: 20, right: 0, bottom: 20, left: 0 }, HOUR = 36e5;

		$scope.graph_width = $scope.width || 1100, $scope.graph_height = $scope.height || 340, $scope.legend_height = 0;

		var width, height, x, y, svg, base = d3.select("#" + $scope.id + ' .plot.loaded').append("svg");

		var set_dimensions = function() {
			width = $scope.graph_width - margin.left - margin.right;
			height = $scope.graph_height - margin.top - margin.bottom;
			x = d3.time.scale().range([0, width]);
			y = d3.scale.linear().range([height, 0]);
			base.attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom);
			svg && svg.selectAll("*").remove();
			svg = base.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");
		};
		set_dimensions();
		$scope.show_loading = !0;

		var na_before, time_format = d3.time.format.utc("%b %d"), delta = 0, padding = 0;

		// $scope.$watch("summary", function(summary) {
		// 	summary && (margin.top = 25, set_dimensions())
		// });

		// $scope.$watch("legend", function(legend) {
		// 	legend && ($scope.legend_height = 30)
		// });

		$scope.$watch("data", function(data) {
			/*jQuery($element).find(".inner").unbind(".cursor"),*/
			if (svg.selectAll("*").remove(), Array.isArray(data) && 0 !== data.length) {

				console.log('Working');
				// if (na_before = null, $scope.nabefore && "[object Date]" === Object.prototype.toString.call($scope.nabefore) && !isNaN($scope.nabefore.getTime()) && (na_before = $scope.nabefore), $scope.padding && (x = d3.time.scale().range([0, width * (1 - 1 / data.length)])), x.domain(d3.extent(data, function(d) {
				// 		return d.date
				// 	})), data.length <= 1 && x.domain([new Date(data[0].date.getTime() - 24 * HOUR), new Date(data[0].date.getTime() + 24 * HOUR)]), $scope.dynamic)
				// {
				// 	var min = d3.min(data, function(d) {
				// 		return na_before && $filter("g_aggregate_date")(d.date, "1d") < $filter("g_aggregate_date")(na_before, "1d") ? void 0 : $scope.multiple ? d3.min(d.values) : d.value - (d.decr || 0)
				// 		});
				// 	var max = d3.max(data, function(d) {
				// 		return na_before && $filter("g_aggregate_date")(d.date, "1d") < $filter("g_aggregate_date")(na_before, "1d") ? void 0 : $scope.multiple ? d3.max(d.values) : d.value + (d.incr || 0)
				// 	});

				// 	delta = max - min === 0 ? .2 * max : .2 * (max - min);
				// 	y.domain([Math.max(0 !== min ? min - delta : min, 0) || 0, max + delta || 0]);

				// } else delta = 0, y.domain([0, d3.max(data, function(d) {
				// 	return $scope.multiple ? d3.max(d.values) : d.value + (d.incr || 0)
				// })]);
				// if (time_format = d3.time.format.utc("%b %d"), (x.domain()[1] - x.domain()[0]) 	/ 5 < 1728e5 && (time_format = d3.time.format.utc("%b %d")), x.domain()[1] - x.domain()[0] > 1728e7 && (time_format = d3.time.format.utc("%b %d %Y")), drawAxis(data), $scope.multiple)
				// 	for (var i = data[0].values.length - 1; i >= 0; i--) drawPlot(data, i);
				// else drawPlot(data);
				// setupBubbleCursor(data), 
				$scope.show_loading = !1;
			}
		});

		// var drawAxis = function(data) {
		// 	var X_TICKS = 5,
		// 		x_masters = data.filter(function(d, i) {
		// 			return i % Math.floor(data.length / X_TICKS) === 0 && i <= Math.floor((X_TICKS - 1) * data.length / X_TICKS)
		// 		}),
		// 		x_normals = data.filter(function(d, i) {
		// 			return !(i % Math.floor(data.length / X_TICKS) === 0 && i <= Math.floor((X_TICKS - 1) * data.length / X_TICKS))
		// 		});
		// 	$scope.padding && data.length > 1 && (padding = width / data.length / 2), svg.append("g").attr("class", "x axis").attr("transform", "translate(0, -" + height + ")").selectAll(".tick").data(x_masters).enter().append("svg:line").attr("class", "line").attr("x1", function(d) {
		// 		return x(d.date)
		// 	}).attr("x2", function(d) {
		// 		return x(d.date)
		// 	}).attr("y1", 0).attr("y2", 2 * height), svg.append("g").attr("class", "x axis").attr("transform", "translate(0, " + y.range()[0] + ")").selectAll(".tick").data(x_normals).enter().append("svg:line").attr("class", "tick").attr("x1", function(d) {
		// 		return x(d.date)
		// 	}).attr("x2", function(d) {
		// 		return x(d.date)
		// 	}).attr("y1", 0).attr("y2", 3), svg.append("g").attr("class", "x axis").attr("transform", "translate(0, " + y.range()[0] + ")").append("svg:line").attr("x1", x.range()[0]).attr("y1", 0).attr("x2", x.range()[1]).attr("y2", 0), svg.append("g").attr("class", "x axis").attr("transform", "translate(0, " + y.range()[0] + ")").selectAll(".tick").data(x_masters).enter().append("svg:line").attr("class", "master tick").attr("x1", function(d) {
		// 		return x(d.date) + padding
		// 	}).attr("x2", function(d) {
		// 		return x(d.date) + padding
		// 	}).attr("y1", 0).attr("y2", 18), svg.append("g").attr("class", "x axis").attr("transform", "translate(0, " + y.range()[0] + ")").selectAll(".value").data(x_masters).enter().append("svg:text").attr("class", "value").attr("x", function(d) {
		// 		return x(d.date) + padding + 5
		// 	}).attr("y", 15).text(function(d) {
		// 		return time_format(d.date)
		// 	});
		// 	for (var g_yaxis = svg.append("g").attr("class", "y axis"), Y_TICKS = 3, y_ticks = [], i = 1; Y_TICKS >= i; i++)
		// 		if ($scope.dynamic) {
		// 			var tick = Math.floor(y.domain()[1]) - Math.floor((y.domain()[1] - 2 * delta - y.domain()[0]) / Y_TICKS) * i;
		// 			isNaN(tick) || y_ticks.push(tick)
		// 		} else y_ticks.push(y.range()[1] + Math.floor(y.domain()[1] / Y_TICKS) * i);
		// 	g_yaxis.selectAll(".tick").data(y_ticks).enter().append("svg:line").attr("class", "line").attr("x1", -2).attr("x2", width).attr("y1", function(d) {
		// 		return y(d)
		// 	}).attr("y2", function(d) {
		// 		return y(d)
		// 	}), g_yaxis.selectAll(".tick").data(y_ticks).enter().append("svg:line").attr("class", "tick").attr("x1", -2).attr("x2", 15).attr("y1", function(d) {
		// 		return y(d)
		// 	}).attr("y2", function(d) {
		// 		return y(d)
		// 	}), g_yaxis.selectAll(".value").data(y_ticks).enter().append("svg:text").attr("class", "value").attr("x", 1).attr("y", function(d) {
		// 		return y(d) + 10
		// 	}).text(function(d) {
		// 		return $filter("bignumber")(d, 3)
		// 	})
		// };

		// var drawPlot = function(data, index) {

		// 	if (data.length <= 1) {
		// 		var g_all = svg.append("svg:g"),
		// 			dot_class = "dot" + ($scope.multiple ? " dot" + index : "");
		// 		return void g_all.selectAll(".dot").data(data).enter().append("circle").attr("class", dot_class).attr("cx", function(d) {
		// 			return x(d.date)
		// 		}).attr("cy", function(d) {
		// 			var value = $scope.multiple ? d.values[index] : d.value;
		// 			return y("number" == typeof value ? value : 0)
		// 		}).attr("r", 2)
		// 	}

		// 	var line = d3.svg.line().x(function(d) {
		// 			return x(d.date) + padding
		// 		}).y(function(d) {
		// 			var value = $scope.multiple ? d.values[index] : d.value;
		// 			return y("number" == typeof value ? value : 0)
		// 		}),
		// 		area = d3.svg.area().x(function(d) {
		// 			return x(d.date) + padding
		// 		}).y0(y.range()[0]).y1(function(d) {
		// 			var value = $scope.multiple ? d.values[index] : d.value;
		// 			return y("number" == typeof value ? value : 0)
		// 		});

		// 	if (!$scope.multiple) var incr = d3.svg.area().x(function(d) {
		// 			return x(d.date) + padding
		// 		}).y0(function(d) {
		// 			return y(d.value)
		// 		}).y1(function(d) {
		// 			return y(d.value + (d.incr || 0))
		// 		}),
		// 		decr = d3.svg.area().x(function(d) {
		// 			return x(d.date) + padding
		// 		}).y0(function(d) {
		// 			return y(d.value)
		// 		}).y1(function(d) {
		// 			return y(d.value - (d.decr || 0))
		// 		});

		// 	var past = d3.svg.area().interpolate("basis").x(function(d) {
		// 			return x(d.date) + padding
		// 		}).y0(y.range()[0]).y1(function(d) {
		// 			return y(d.past || 0)
		// 		}),
		// 		d_last = [],
		// 		d_done = [],
		// 		d_all = [];

		// 	if (na_before)
		// 		for (var i = 0; i < data.length; i++) $filter("g_aggregate_date")(data[i].date, "1d") >= $filter("g_aggregate_date")(na_before, "1d") ? (d_done.push(data[i]), d_all.push(data[i])) : data[i].na = !0;
		// 	else d_done = data.slice(0), d_all = data.slice(0);

		// 	if ($scope.last && d_done.length > 1 && (d_last.unshift(d_done.pop()), d_last.unshift(d_done[d_done.length - 1])), na_before) {
		// 		if (!$scope.multiple || $scope.multiple && 0 === index) {
		// 			var width;
		// 			width = x(d_all.length > 0 ? d_all[0].date : x.domain()[1]);
		// 			var g_na = svg.append("svg:rect");
		// 			g_na.attr("x", 0 - margin.right).attr("y", 0 - margin.top).attr("width", width).attr("height", height + margin.top).attr("class", "na")
		// 		}
		// 	} else {
		// 		var g_past = svg.append("svg:g");
		// 		g_past.append("svg:path").attr("d", past(d_all)).classed("past", !0)
		// 	}

		// 	if (!$scope.multiple) {
		// 		var g_incr = svg.append("svg:g");
		// 		g_incr.append("svg:path").attr("d", incr(d_all)).classed("incr", !0);
		// 		var g_decr = svg.append("svg:g");
		// 		g_decr.append("svg:path").attr("d", decr(d_all)).classed("decr", !0)
		// 	}

		// 	var g_last = svg.append("svg:g");

		// 	if (g_last.append("svg:path").attr("d", line(d_last)).classed("line last", !0), $scope.noarea || g_last.append("svg:path").attr("d", area(d_last)).classed("area last", !0), !$scope.nodeco || 0 === index) {
		// 		var dot_class = "dot last" + ($scope.multiple ? " dot" + index : "");
		// 		g_last.selectAll(".dot").data(d_last).enter().append("circle").attr("class", dot_class).attr("cx", function(d) {
		// 			return x(d.date) + padding
		// 		}).attr("cy", function(d) {
		// 			var value = $scope.multiple ? d.values[index] : d.value;
		// 			return y("number" == typeof value ? value : 0)
		// 		}).attr("r", 2)
		// 	}

		// 	var g_all = svg.append("svg:g");

		// 	if (!$scope.nodeco || 0 === index) {
		// 		var path = g_all.append("svg:path").attr("d", line(d_done)).classed("line", !0);
		// 		$scope.multiple && path.classed("line" + index, !0)
		// 	}

		// 	if (!$scope.noarea) {
		// 		var area = g_all.append("svg:path").attr("d", area(d_done)).classed("area", !0);
		// 		$scope.multiple && area.classed("area" + index, !0)
		// 	}

		// 	if (!$scope.nodeco || 0 === index) {
		// 		var dot_class = "dot" + ($scope.multiple ? " dot" + index : "");
		// 		g_all.selectAll(".dot").data(d_done).enter().append("circle").attr("class", dot_class).attr("cx", function(d) {
		// 			return x(d.date) + padding
		// 		}).attr("cy", function(d) {
		// 			var value = $scope.multiple ? d.values[index] : d.value;
		// 			return y("number" == typeof value ? value : 0)
		// 		}).attr("r", 2)
		// 	}
		// };

		// var setupBubbleCursor = function(data) {
		// 	var g_cursor = svg.append("g"),
		// 		cursor = g_cursor.append("svg:line").attr("class", "cursor").attr("x1", 0).attr("x2", 0).attr("y1", -height).attr("y2", 2 * height).style("visibility", "hidden"),
		// 		c_over = function() {
		// 			cursor.style("visibility", "visible"), d3.select(jQuery($element).find(".bubble")[0]).style("visibility", "visible")
		// 		},
		// 		c_out = function() {
		// 			cursor.style("visibility", "hidden"), d3.select(jQuery($element).find(".bubble")[0]).style("visibility", "hidden")
		// 		},
		// 		c_move = function(xpos) {
		// 			var t = x.invert(xpos),
		// 				min = t - data[0].date,
		// 				dst = 0;
		// 			if (data.forEach(function(p, i) {
		// 					min > Math.abs(t - p.date) && (min = Math.abs(t - p.date), dst = i)
		// 				}), $scope.multiple) {
		// 				var bubble_class = "bubble" + ("undefined" == typeof data[dst].na ? " multiple" : "");
		// 				d3.select(jQuery($element).find(".bubble")[0]).attr("class", bubble_class)
		// 			} else d3.select(jQuery($element).find(".bubble")[0]).attr("class", "bubble");
		// 			if ($scope.multiple) var top = y(y.domain()[0] + (y.domain()[1] - y.domain()[0]) / 2) - jQuery($element).find(".bubble:first").height() / 2;
		// 			else var top = y(data[dst].na ? y.domain()[0] + (y.domain()[1] - y.domain()[0]) / 2 : data[dst].value);
		// 			$scope.summary && (top += 15), d3.select(jQuery($element).find(".bubble")[0]).style("top", top + "px").style("left", x(data[dst].date) + padding + 11 + "px");
		// 			var top_tail = jQuery($element).find(".bubble:first").height() / 2;
		// 			d3.select(jQuery($element).find(".bubble .tail")[0]).style("top", top_tail - 5 + "px");
		// 			var html = "";
		// 			if (html += '<div class="date">', html += time_format(data[dst].date), html += "</div>", $scope.multiple && "undefined" == typeof data[dst].na) {
		// 				html += '<div class="values">';
		// 				for (var i = 0; i < data[dst].values.length; i++) html += '<div class="line">', html += '<span class="dot dot' + i + '"></span>', html += '<span class="title">' + $scope.labels[i] + "</span>", html += '<span class="value">', html += "number" == typeof data[dst].values ? $filter("number")(data[dst].values[i]) : data[dst].values[i], html += "</span>", html += "</div>";
		// 				html += "</div>"
		// 			} else html += '<div class="value">', html += "undefined" != typeof data[dst].na ? "N/A" : $filter("number")(data[dst].value), html += "</div>", data[dst].extra && (html += '&nbsp;<div class="extra">', html += data[dst].extra, html += "</div>"), "undefined" != typeof data[dst].na && (html += '&nbsp;<span class="info">(date is before tracker creation)</span>'), "undefined" == typeof data[dst].na && "undefined" != typeof data[dst].incr && "undefined" != typeof data[dst].decr && (html += " &nbsp ", html += '<div class="value incr">', html += "+" + $filter("bignumber")(data[dst].incr), html += "</div>/", html += '<div class="value decr">', html += "-" + $filter("bignumber")(data[dst].decr), html += "</div>");
		// 			d3.select(jQuery($element).find(".bubble .content")[0]).html(html), "undefined" != typeof data[dst].incr, cursor.attr("x1", x(data[dst].date) + padding).attr("x2", x(data[dst].date) + padding)
		// 		},
		// 		inner = jQuery($element).find(".inner");
		// 	inner.bind("mouseover.cursor", function() {
		// 		$scope.$parent.$broadcast("graph:c_over", $scope), c_over()
		// 	}), inner.bind("mousemove.cursor", function(evt) {
		// 		var xpos = evt.clientX - inner.offset().left - 13;
		// 		$scope.$parent.$broadcast("graph:c_move", $scope, xpos), c_move(xpos)
		// 	}), inner.bind("mouseout.cursor", function() {
		// 		$scope.$parent.$broadcast("graph:c_out", $scope), c_out()
		// 	}), $scope.$on("graph:c_over", function(evt, origin) {
		// 		origin !== $scope && c_over()
		// 	}), $scope.$on("graph:c_move", function(evt, origin, xpos) {
		// 		origin !== $scope && c_move(xpos)
		// 	}), $scope.$on("graph:c_out", function(evt, origin) {
		// 		origin !== $scope && c_out()
		// 	})
		// };

		// $scope.$watch("loading", function(value, old_value) {
		// 	value !== old_value && ($scope.show_loading = value)
		// })

	}])

	.directive("lineChart", function($window) {
		return{
			restrict: "E",
			replace: !0,
			scope: {
				data: "=data",
				id: "@id",
				// labels: "=labels",
				// multiple: "=multiple",
				// summary: "=summary",
				// caption: "@caption",
				// last: "=last",
				// help: "@help",
				// loading: "=loading",
				width: "=width",
				height: "=height",
				// inline: "=inline",
				// nabefore: "=nabefore",
				// dynamic: "=dynamic",
				// noarea: "=noarea",
				// nodeco: "=nodeco",
				// legend: "=legend",
				// padding: "=padding"
			},
			templateUrl: "http://www.owloo.com/dev/3.1/app/partials/directives/line-chart.html",
			controller: "LineChartController"
		};
	});

});