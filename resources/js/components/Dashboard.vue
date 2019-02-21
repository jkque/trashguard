<template>
    <div>
        <div class="d-container">
            <div class="row align-items-center ">
                <div class="col-6">
                    <h4 style="margin: 0px;">Overview</h4>
                </div>
                <div class="col-6">
                    <div class="clearfix">
                        <div class="pull-right">
                            <button type="button" @click="$router.push('/addReport')" class="btn btn-primary">Create Report</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="col-3" v-for="(category,index) in categories" :key="index">
                    <div class="card card-category">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ category.type_name }}</h6>
                                    <h5 class="card-title">{{ Number((category.count/all)*100).toFixed(2) }}<small>%</small></h5>
                                </div>
                                <div class="col-4">
                                    <h3 class="card-icon"><i :class="[category.icon]"></i></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h4>Monthly Report</h4>
                                </div>
                                <div class="col-6">
                                    <div class="clearfix">
                                        <div class="pull-right">
                                            <p class="text-right text-report-this-year">{{ year_reports }} </p>
                                            <p class="text-right text-report-this-year-sub">{{ (year_reports > 1)? 'Reports':'Report' }} this year</p>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <canvas id="monthly-reports"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h4>Top places</h4>
                            <p style="margin: 0px;">Ranking for number of reports in every place</p>
                        </div>
                        <div class="">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item" v-for="(location,index) in location_ranking" :key="index">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="rank">
                                                <div class="rank-number">{{ index+1 }}</div>
                                                <div class="rank-badge">
                                                    {{ Number((location.count/all)*100).toFixed(0) }}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <p style="margin-bottom: 0px;"><strong>{{ location.place }}</strong></p>
                                            <p style="color: #7f8c8d; margin: 0px;"><small>{{ location.count }} Reports</small></p>
                                            <div class="progress" style="height: 3px;">
                                                <div class="progress-bar" role="progressbar" :style="{ width: Number((location.count/all)*100).toFixed(2)+'%' }" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js';
    export default {
        data(){
            return {
                categories: [],
                monthly_reports: [],
                chart: null,
                year_reports: 0,
                location_ranking: [],
                all: 0,
            }
        },
        mounted(){
            let self = this;
            Chart.defaults.global.legend.display = false;
            this.chart = new Chart($("#monthly-reports"), {
                type: 'roundedBar',
                data: {
                    labels:[],
                    datasets:[{
                        label: "# of Reports",
                        data: [],
                        backgroundColor: [],
                    }]
                },
                options:{ 
                    scales: {
                        xAxes: [{
                            gridLines : {
                                display : false,
                                drawBorder: false,
                                tickMarkLength: 10
                            },
                            ticks: {
                                padding: 10
                            },
                            barThickness: 15,
                        }],
                        yAxes: [{
                            ticks: { 
                                beginAtZero: true,
                                padding: 25
                            },
                            gridLines: {
                                drawBorder: false,
                                zeroLineColor: 'transparent',
                            }
                        }]
                    },
                    barRoundness: 1.5,
                    
                }
            });
        },
        created(){
            this.chartInitialization();
            this.getCategoryCounts((response) => {
                this.bindIcons();
            });
            this.getMonthlyReports((response) => {
                this.bindMonthlyReportChart();
            });
            this.getLocationRanking((response) => {

            });
        },
        methods: {
            bindMonthlyReportChart(){
                let self = this;
                $(self.monthly_reports).each((index,category) => {
                    if(self.chart){
                        self.chart.data.labels.push(category.month);
                        self.chart.data.datasets.forEach((dataset) => {
                            dataset.data.push(category.count);
                            dataset.backgroundColor.push("#f8d13a");
                        });
                        self.chart.update();
                    }
                    // self.monthly_reports.labels.push(category.month);
                    // self.monthly_reports.datasets[0].data.push(category.count);
                    // self.monthly_reports.datasets[0].backgroundColor.push('#f8d13a');
                });
            },

            bindIcons(){
                let self = this;
                $(this.categories).each((index,category) => {
                    if(category.type == 0){
                        self.$set(self.categories[index],'icon','far fa-flag');
                    } else if(category.type == 1){
                        self.$set(self.categories[index],'icon','far fa-check-circle');
                    } else if(category.type == 2){
                        self.$set(self.categories[index],'icon','fas fa-history');
                    } else if(category.type == 3){
                        self.$set(self.categories[index],'icon','far fa-times-circle');
                    }
                });
            },
            
            getCategoryCounts(callable = null){
                let self = this;
                axios.get('/d/category_count').then((response)=>{
                    self.categories = response.data.data;
                    if(callable){ callable(response); }
                }).catch((errors)=>{

                });
            },

            getMonthlyReports(callable = null){
                let self = this;
                axios.get('/d/monthly_reports').then((response)=>{
                    self.monthly_reports = response.data.data;
                    self.year_reports = response.data.year_reports;
                    if(callable){ callable(response); }
                }).catch((errors)=>{

                });
            },

            getLocationRanking(callable = null){
                let self = this;
                axios.get('/d/location_ranking').then((response)=>{
                    self.location_ranking = response.data.ranking;
                    self.all = response.data.all;
                    if(callable){ callable(response); }
                }).catch((errors)=>{

                });
            },

            chartInitialization(){
                // draws a rectangle with a rounded top
                Chart.helpers.drawRoundedTopRectangle = function(ctx, x, y, width, height, radius) {
                    ctx.beginPath();
                    if(radius > height/2){
                        radius = height/2;
                    }if(radius > width/2){
                        radius = width/2;
                    }
                    ctx.moveTo(x + radius, y);
                    // top right corner
                    ctx.lineTo(x + width - radius, y);
                    ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                    // bottom right	corner
                    ctx.lineTo(x + width, y + height - radius);
                    ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
                    // bottom left corner
                    ctx.lineTo(x + radius, y + height);
                    ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                    // top left	
                    ctx.lineTo(x, y + radius);
                    ctx.quadraticCurveTo(x, y, x + radius, y);
                    ctx.closePath();
                };

                Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
                    draw: function() {
                        var ctx = this._chart.ctx;
                        var vm = this._view;
                        var left, right, top, bottom, signX, signY, borderSkipped;
                        var borderWidth = vm.borderWidth;

                        if (!vm.horizontal) {
                            // bar
                            left = vm.x - vm.width / 2;
                            right = vm.x + vm.width / 2;
                            top = vm.y;
                            bottom = vm.base;
                            signX = 1;
                            signY = bottom > top? 1: -1;
                            borderSkipped = vm.borderSkipped || 'bottom';
                        } else {
                            // horizontal bar
                            left = vm.base;
                            right = vm.x;
                            top = vm.y - vm.height / 2;
                            bottom = vm.y + vm.height / 2;
                            signX = right > left? 1: -1;
                            signY = 1;
                            borderSkipped = vm.borderSkipped || 'left';
                        }

                        // Canvas doesn't allow us to stroke inside the width so we can
                        // adjust the sizes to fit if we're setting a stroke on the line
                        if (borderWidth) {
                            // borderWidth shold be less than bar width and bar height.
                            var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                            borderWidth = borderWidth > barSize? barSize: borderWidth;
                            var halfStroke = borderWidth / 2;
                            // Adjust borderWidth when bar top position is near vm.base(zero).
                            var borderLeft = left + (borderSkipped !== 'left'? halfStroke * signX: 0);
                            var borderRight = right + (borderSkipped !== 'right'? -halfStroke * signX: 0);
                            var borderTop = top + (borderSkipped !== 'top'? halfStroke * signY: 0);
                            var borderBottom = bottom + (borderSkipped !== 'bottom'? -halfStroke * signY: 0);
                            // not become a vertical line?
                            if (borderLeft !== borderRight) {
                                top = borderTop;
                                bottom = borderBottom;
                            }
                            // not become a horizontal line?
                            if (borderTop !== borderBottom) {
                                left = borderLeft;
                                right = borderRight;
                            }
                        }

                        // calculate the bar width and roundess
                        var barWidth = Math.abs(left - right);
                        var roundness = this._chart.config.options.barRoundness || 0.5;
                        var radius = barWidth * roundness * 0.5;
                        
                        // keep track of the original top of the bar
                        var prevTop = top;
                        
                        // move the top down so there is room to draw the rounded top
                        top = prevTop + radius;
                        var barRadius = top - prevTop;

                        ctx.beginPath();
                        ctx.fillStyle = vm.backgroundColor;
                        ctx.strokeStyle = vm.borderColor;
                        ctx.lineWidth = borderWidth;

                        // draw the rounded top rectangle
                        Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth, bottom - prevTop, barRadius);

                        ctx.fill();
                        if (borderWidth) {
                        ctx.stroke();
                        }

                        // restore the original top value so tooltips and scales still work
                        top = prevTop;
                    },
                });

                Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);

                Chart.controllers.roundedBar = Chart.controllers.bar.extend({
                    dataElementType: Chart.elements.RoundedTopRectangle
                });
            }

            // 
        }
    }
</script>

<style lang="scss" scoped>
    .d-container {
        padding: 25px;
        height: calc(100vh - 56px);
        overflow: auto;
    }
    .card-category {
        .card-subtitle {
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 600;
            color: #95aac9 !important;
            letter-spacing: .08em;
            margin-bottom: 3px !important;
        }
        .card-title {
            font-weight: 800;
            font-size: 28px;
            margin-bottom: 0px;
        }
        .card-icon {
            text-align: center;
            color: #95aac9 !important;
        }
    }
    
    .text-report-this-year {
        font-size: 24px;
        margin-bottom :0px;
    }
    .text-report-this-year-sub {
        color: #95aac9;
        margin-bottom: 0px;
    }

    .rank {
        position: relative;
        .rank-badge {
            position: absolute;
            bottom: -5px;
            left: 27px;
            font-size: 10px;
            padding: 1px 7px;
            border-radius: 15px;
            background-color: #c0392b;
            box-shadow: 0px 0px 0px 2px #fff;
            color: #fff;
        }
        .rank-number {
            height: 40px;
            width: 40px;
            background-color: #f8d13a;
            border-radius: 50%;
            color: #FFF;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            display:flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>