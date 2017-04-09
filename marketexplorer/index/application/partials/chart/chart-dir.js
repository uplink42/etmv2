app.directive('chart', [
    'config',
    'marketHistoryFact', 
    function(config, marketHistoryFact) {
        "use strict";

        return {
            templateUrl: config.dist + '/partials/chart/chart-view.html',
            restrict: 'E',
            scope: {
                item: '=',
                region: '='
            },
            controller: ['$scope', function($scope) {
                $scope.$watch('item', function(newValue, oldValue) {
                    if (Object.keys(newValue).length && !angular.isUndefined(newValue)) {
                        getHistory();
                    }
                }, true);

                $scope.$watch('region', function(newValue, oldValue) {
                    if (Object.keys(newValue).length && !angular.isUndefined(newValue)) {
                        console.log(newValue);
                        getHistory();
                    }
                }, true);


                function getHistory() {
                    marketHistoryFact
                    .queryItem($scope.region, $scope.item.id)
                    .then(function(response) {
                        buildChart(response);
                    })
                    .catch(function(error) {
                        console.error(error.stack);
                    });
                }


                function buildChart(data) {
                    var threeMonthsAgo = moment().subtract(3, 'month');
                    var volumes       = [],
                        dates         = [],
                        avgPrices     = [],
                        spread        = [];

                    angular.forEach(data, function(cValue, cKey) {
                        if (moment(cValue.date).isAfter(threeMonthsAgo)) {
                            dates.push({label: moment(cValue.date).format('DD/MM')});
                            volumes.push({value: cValue.volume});
                            avgPrices.push({value: cValue.avgPrice});
                            spread.push({value: parseFloat(cValue.highPrice) - parseFloat(cValue.lowPrice)});
                        }
                    });
                    
                    var chartData = {
                        chart: {
                            subcaptionFontBold: "0",
                            paletteColors: "#0075c2,#1aaf5d,#f2c500",
                            anchorAlpha: '0',
                            xAxisname: "time",
                            pYAxisName: "quantity",
                            sYAxisName: "price",
                            sNumberSuffix: " ISK",
                            showAlternateHGridColor: "0",
                            showPlotBorder: "0",
                            labelFontColor: "#fff",
                            labelFontSize: "14",
                            legendItemFontSize: "14",
                            usePlotGradientColor: "0",
                            baseFontColor: "#333333",
                            baseFont: "Helvetica Neue,Arial",
                            showBorder: "0",
                            showShadow: "0",
                            showCanvasBorder: "0",
                            legendBorderAlpha: "0",
                            legendShadow: "0",
                            showValues: "0",
                            divlineAlpha: "100",
                            divlineColor: "#999999",
                            divlineThickness: "1",
                            divLineDashed: "1",
                            divLineDashLen: "1",
                            numVisiblePlot: "12",
                            flatScrollBars: "1",
                            scrollheight: "10",
                            linethickness: "2",
                            formatnumberscale: "1",
                            labeldisplay: "ROTATE",
                            slantlabels: "1",
                            divLineAlpha: "40",
                            anchoralpha: "0",
                            animation: "1",
                            legendborderalpha: "20",
                            drawCrossLine: "1",
                            crossLineColor: "#f6a821",
                            crossLineAlpha: "100",
                            tooltipGrayOutColor: "#80bfff",
                            canvasBgAlpha: "0",
                            bgColor: "#32353d",
                            bgAlpha: "100",
                            legendBgColor: "#333",
                            outCnvBaseFontColor: "#fff",
                            outCnvBaseFontSize: "12",
                            pyaxisnamefontcolor: "#fff",
                            syaxisnamefontcolor: "#fff",
                            pyaxisnamefontsize: "16",
                            syaxisnamefontsize: "16",
                            captionFontColor: "#fff",
                        },
                        categories: [{
                            category: dates
                            }
                        ],
                        dataset: [{
                            seriesName: 'volume',
                            parentYAxis: "P",
                            data: volumes
                            }, {
                            seriesName: 'avg',
                            parentYAxis: "S",
                            renderAs: 'line',
                            data: avgPrices
                            },{
                            seriesName: 'spread',
                            parentYAxis: "S",
                            renderAs: 'line',
                            data: spread
                            }, /*{
                                seriesName: 'highest',
                                parentYAxis: "S",
                                renderAs: 'line',
                                data: highestPrices
                            }*/
                        ]
                    };

                    console.log(chartData);

                    FusionCharts.ready(function () {
                        var chart = new FusionCharts({
                            type: 'mscombidy2d',
                            renderAt: 'chart',
                            width: '100%',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: chartData
                        });
                        chart.render();
                    });
                }
            }],

            link: function() {
            }
        };
    }
]);