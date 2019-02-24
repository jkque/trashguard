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
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-body">
                            <div class="input-group">
                                <gmap-autocomplete
                                    class="form-control"
                                    style="margin-bottom: 4px"
                                    placeholder="Search reports at.."
                                    @place_changed="setPlace">
                                </gmap-autocomplete>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ selectedOptionType }} <span class="caret"></span></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" @click="selectMapReportType($event,0)">Pending</a>
                                        <a class="dropdown-item" href="#" @click="selectMapReportType($event,2)">On-going</a>
                                        <a class="dropdown-item" href="#" @click="selectMapReportType($event,1)">Solved</a>
                                        <a class="dropdown-item" href="#" @click="selectMapReportType($event,3)">Declined</a>
                                    </div>
                                </div><!-- /btn-group -->
                            </div>
                            <p style="color: #7f8c8d; font-size: 12px;" v-if="responseText">{{ responseText }}</p>
                            <gmap-map :center="center" :zoom="12" style="width:100%;  height: 400px; margin-bottom: 3px;" v-bind:options="mapStyle">
                                <gmap-marker  :key="index" v-for="(m, index) in markers"  :position="m.position" @click="center=m.position" ></gmap-marker>
                                <GmapCircle
                                    :center="center"
                                    :radius="mapRadius"
                                    :visible="true"
                                    :options="circleOtions"
                                ></GmapCircle>
                            </gmap-map>
                            <p style="margin: 0;"><small>All reports shown are under 2 km radius based on a specified location.</small></p>
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
                type_name: ['Pending', 'Solved', 'On-going', 'Declined'],
                categories:         [],
                monthly_reports:    [],
                chart:              null,
                year_reports:       0,
                location_ranking:   [],
                all:                0,
                center: { lat: 10.3157, lng: 123.8854 },
                markers: [],
                places: [],
                currentPlace: null,
                icons:          [],
                circleOtions: {
                    strokeColor: '#9b7b00',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor:'#000',
                    fillOpacity:0.1
                },
                currentLocation: 'Cebu City, Cebu',
                selectedOptionType: 'Report Type',
                responseText: '',
                mapRadius: 2000,
                mapStyle: {zoom: 13,styles: [{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.province","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.attraction","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.attraction","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"poi.business","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.business","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"poi.government","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.government","elementType":"labels.icon","stylers":[{"color":"#ff0000"}]},{"featureType":"poi.medical","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.medical","elementType":"labels.icon","stylers":[{"color":"#ff0000"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.park","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"poi.place_of_worship","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.school","elementType":"labels.text.fill","stylers":[{"color":"#080808"}]},{"featureType":"poi.sports_complex","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#ffcf15"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffe891"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"transit.line","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"transit.station.airport","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"transit.station.bus","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"transit.station.rail","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#2d2d2d"}]}]}
            }
        },
        mounted(){
            let self = this;
            this.geolocate();
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
            
            setTimeout(() => {
                this.getMarkers();
            },1000); 

           
        },
        created(){
            
            this.icons = [
                this.$parent.root_url + '/images/g-icon-pending.png?raw=true',
                this.$parent.root_url + '/images/g-icon-solved.png?raw=true',
                this.$parent.root_url + '/images/g-icon-on-going.png?raw=true',
                this.$parent.root_url + '/images/g-icon-declined.png?raw=true',
            ];
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
            setPlace(place){
                let self = this;
                self.currentLocation = place.formatted_address;
                self.$set(self.center,'lat',parseFloat(place.geometry.location.lat()));
                self.$set(self.center,'lng',parseFloat(place.geometry.location.lng()));
                this.currentPlace = place;
                self.getMarkers();
            },

            selectMapReportType(event,type){
                event.preventDefault();
                
                let self = this;
                self.selectedOptionType = self.type_name[type];
                self.getMarkers(type,()=>{

                });
            },

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
                    console.log(errors);

                });
            },

            getMonthlyReports(callable = null){
                let self = this;
                axios.get('/d/monthly_reports').then((response)=>{
                    self.monthly_reports = response.data.data;
                    self.year_reports = response.data.year_reports;
                    if(callable){ callable(response); }
                }).catch((errors)=>{
                    console.log(errors);

                });
            },

            getLocationRanking(callable = null){
                let self = this;
                axios.get('/d/location_ranking').then((response)=>{
                    self.location_ranking = response.data.ranking;
                    self.all = response.data.all;
                    if(callable){ callable(response); }
                }).catch((errors)=>{
                    console.log(errors);
                });
            },

            getMarkers(type = 0, callable = null ){
                let self = this;
                axios.get('/d/getReportByPlace',{params:{ type: type, lng: self.center.lng, lat: self.center.lat }}).then((response)=>{
                    if(response.status  == 200){
                        self.markers = [];

                        self.responseText = "Showing "+response.data.reports.length+' '+self.type_name[type]+' Reports in '+self.currentLocation;
                        $(response.data.reports).each((index,report) => {
                            if(report.longitude && report.latitude){
                                self.markers.push(new google.maps.Marker({
                                    icon: {
                                        url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                                        // This marker is 20 pixels wide by 32 pixels high.
                                        size: new google.maps.Size(20, 32),
                                        // The origin for this image is (0, 0).
                                        origin: new google.maps.Point(0, 0),
                                        // The anchor for this image is the base of the flagpole at (0, 32).
                                        anchor: new google.maps.Point(0, 32)
                                    } ,
                                    position: {
                                        lat: parseFloat(report.latitude),
                                        lng: parseFloat(report.longitude)
                                    },
                                }));
                            }
                        });
                    }
                    if(callable){ callable(response); }
                }).catch((errors)=>{
                    console.log(errors);
                });
            },

            geolocate: function() {
                // navigator.geolocation.getCurrentPosition(position => {
                //     this.center = {
                //         lat: position.coords.latitude,
                //         lng: position.coords.longitude
                //     };
                // });
                 if ("geolocation" in navigator){ //check geolocation available 
                    //try to get user current location using getCurrentPosition() method
                    navigator.geolocation.getCurrentPosition(function(position){ 
                        self.currentLocation = position;
                        console.log(self.currentLocation);
                    });
                }else{
                    console.log("Browser doesn't support geolocation!");
                }
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