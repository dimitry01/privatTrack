<template>
	<div id="dashboard-analytics">
		<div class="vx-row">

             <div class="vx-col w-full sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
				<statistics-card-line icon="BookOpenIcon" :statistic="opens" statisticTitle="Opens" :chartData="analyticsData.opensDesign" color='warning' type='area'></statistics-card-line>
			</div>

            <div class="vx-col w-full sm:w-full md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
				<statistics-card-line icon="PlayIcon" :statistic="clicks" statisticTitle="Clicks" :chartData="analyticsData.clicksDesign" color="primary" type='area'></statistics-card-line>
			</div>

           
		</div>

        <div class="vx-row">
            <div class="vx-col w-full lg:w-1/3 mb-base">
                <vx-card title="Actions Statistics">

                    <div slot="no-body">
                        <vue-apex-charts type=radialBar height=370 :options="analyticsData.actionsStatsRadial.chartOptions" :series="actionsData.series" />
                    </div>

                    <ul>
                        <li v-for="actionData in actionsData.analyticsData" :key="actionData.actionType" class="flex mb-3 justify-between">
                            <span class="flex items-center">
                                    <span class="inline-block h-4 w-4 rounded-full mr-2 bg-white border-3 border-solid" :class="`border-${actionData.color}`"></span>
                                    <span class="font-semibold">{{ actionData.actionType }}</span>
                            </span>
                            <span>{{ actionData.counts }}</span>
                        </li>
                    </ul>
                </vx-card>
            </div>

			<div class="vx-col w-full lg:w-1/3">
				<vx-card title="Device Statistics">
					<template slot="actions">
                        <events-dropdown event="device" :selected="events.device" v-on:eventChanged="changeEvent" />
					</template>
					<div slot="no-body">
						<vue-apex-charts type=donut height=325 :options="analyticsData.sessionsByDeviceDonut.chartOptions" :series="devicesData.series" />
					</div>
					<ul>
						<li v-for="deviceData in devicesData.analyticsData" :key="deviceData.device" class="flex mb-3">
							<feather-icon :icon="deviceData.icon" :svgClasses="[`h-5 w-5 stroke-current text-${deviceData.color}`]"></feather-icon>
							<span class="ml-2 inline-block font-semibold">{{ deviceData.device }}</span>
							<span class="mx-2">-</span>
							<span class="mr-4">{{ deviceData.sessionsPercentage }}%</span>
						</li>
					</ul>
				</vx-card>
			</div>

            <div class="vx-col md:w-1/3 lg:w-1/3 xl:w-1/3 w-full mb-base">
                <vx-card>
                    <div slot="no-body" class="mt-4">
                        <div class="mt-5">
                            <vs-tabs vs-alignment="fixed">
                                <vs-tab vs-label="Countries">
                                    <div>
                                        <vs-table pagination max-items="7" :data="countriesData">
                                            <template slot="thead">
                                                <vs-th sort-key="country">COUNTRY</vs-th>
                                                <vs-th sort-key="opens">OPENS</vs-th>
                                                <vs-th sort-key="clicks">CLICKS</vs-th>
                                            </template>

                                            <template slot-scope="{data}">
                                                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                                                    <vs-td>
                                                        <span v-if="tr.country == null">Others</span>
                                                        <span v-else>{{tr.country.charAt(0).toUpperCase()}}</span>
                                                    </vs-td>
                                                    <vs-td>
                                                        <span>{{tr.opens}}</span>
                                                    </vs-td>
                                                    <vs-td>
                                                        <span>{{tr.clicks}}</span>
                                                    </vs-td>
                                                </vs-tr>
                                            </template>
                                        </vs-table>
                                    </div>
                                </vs-tab>
                                <vs-tab vs-label="ISP">
                                    <div>
                                        <vs-table pagination max-items="5" :data="ispData">
                                            <template slot="thead">
                                                <vs-th sort-key="isp">ISP</vs-th>
                                                <vs-th sort-key="opens">OPENS</vs-th>
                                                <vs-th sort-key="clicks">CLICKS</vs-th>
                                            </template>

                                            <template slot-scope="{data}">
                                                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                                                    <vs-td>
                                                        <span v-if="tr.isp == null">Others</span>
                                                        <span v-else>{{tr.isp}}</span>
                                                    </vs-td>
                                                    <vs-td>
                                                        <span>{{tr.opens}}</span>
                                                    </vs-td>
                                                    <vs-td>
                                                        <span>{{tr.clicks}}</span>
                                                    </vs-td>
                                                </vs-tr>
                                            </template>
                                        </vs-table>
                                    </div>
                                </vs-tab>
                                <vs-tab vs-label="OS">
                                    <div>
                                        <vs-table pagination max-items="7" :data="osData">
                                            <template slot="thead">
                                                <vs-th sort-key="os">OS NAME</vs-th>
                                                <vs-th sort-key="opens">OPENS</vs-th>
                                                <vs-th sort-key="clicks">CLICKS</vs-th>
                                            </template>

                                            <template slot-scope="{data}">
                                                <vs-tr  :key="indextr" v-for="(tr, indextr) in data">
                                                    <vs-td>
                                                        <span v-if="tr.os == null">Others</span>
                                                        <span v-else>{{tr.os}}</span>
                                                    </vs-td>
                                                    <vs-td>
                                                        <span>{{tr.opens}}</span>
                                                    </vs-td>
                                                    <vs-td>
                                                        <span>{{tr.clicks}}</span>
                                                    </vs-td>
                                                </vs-tr>
                                            </template>
                                        </vs-table>
                                    </div>
                                </vs-tab>
                            </vs-tabs>
                        </div>
                    </div>
                </vx-card>
            </div>

            <div class="vx-col w-full md:w-1/3 lg:w-1/3 xl:w-1/3 mb-base">
                <vx-card title="Browser Statistics" style="min-height:300px">
                    <template slot="actions">
                        <events-dropdown event="browser" :selected="events.browser" v-on:eventChanged="changeEvent" />
					</template>
                    <div v-for="(browser, index) in browsersData" :key="index" :class="{'mt-4': index}">
                        <template v-if="browser.ratio > 0">
                        <div class="flex justify-between">
                            <div class="flex flex-col">
                                <span class="mb-1" v-if="browser.name == 'null'">Others</span>
                                <span class="mb-1" v-else>{{ browser.name }}</span>
                                <h4>{{ browser.ratio }}%</h4>
                            </div>
                        </div>
                        <vs-progress :percent="browser.ratio"></vs-progress>
                        </template>
                    </div>
                </vx-card>
            </div>

            <div class="vx-col md:w-1/3 lg:w-1/3 xl:w-1/3 w-full mb-base">
                <vx-card>
                    <div slot="no-body" class="mt-4">
                        <div class="mt-5">
                            <vs-tabs vs-alignment="fixed">
                                <vs-tab vs-label="CLICK">
                                    <div class="vx-col w-full mb-2">
                                        <vs-button class="inline w-full mb-2" color="primary" type="gradient" v-clipboard:copy="txt_script_click" v-clipboard:success="onCopy">Copy To Clipboard</vs-button>
                                        <vs-textarea disabled class="w-full" height="251px" v-model="txt_script_click"/>
                                    </div>
                                </vs-tab>
                                <vs-tab vs-label="OPEN">
                                    <div class="vx-col w-full mb-2">
                                        <vs-button class="inline w-full mb-2" color="primary" type="gradient" v-clipboard:copy="txt_script_open" v-clipboard:success="onCopy" >Copy To Clipboard</vs-button>
                                        <vs-textarea disabled class="w-full" height="251px" v-model="txt_script_open"/>
                                    </div>
                                </vs-tab>
                            </vs-tabs>
                        </div>
                    </div>
                </vx-card>
            </div>
        </div>

	</div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import StatisticsCardLine from '../components/StatisticsCardLine.vue'
import analyticsData from '../analyticsData.js'
import ChangeTimeDurationDropdown from '../components/ChangeTimeDurationDropdown.vue'
import EventsDropdown from '../components/ChangeEventDropdown.vue'

export default{
	data() {
		return {
            analyticsData: analyticsData,
            type: 'open',
            loading: false,
            txt_script_click: `<?php
                $arr = array();
                $arr['campaign'] = ${this.$route.params.id};
                $arr['email'] = @$_GET['ID_EMAIL_HERE'];
                $arr['data'] = array();
                $arr['data'] ['ip'] = @$_SERVER['REMOTE_ADDR'];
                $arr['data'] ['user_agent'] = @$_SERVER['HTTP_USER_AGENT'];
                $data_string = json_encode($arr);
                $ch = curl_init('https://${window.location.host.split(':')[0]}/api/c_pvt_trck/c_c_em'); 
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
                $result = curl_exec($ch); 

            ?>`,
                txt_script_open: `<?php
                $arr = array();
                $arr['campaign'] = ${this.$route.params.id};
                $arr['email'] = @$_GET['ID_EMAIL_HERE'];
                $arr['data'] = array();
                $arr['data'] ['ip'] = @$_SERVER['REMOTE_ADDR'];
                $arr['data'] ['user_agent'] = @$_SERVER['HTTP_USER_AGENT'];
                $data_string = json_encode($arr);
                $ch = curl_init('https://${window.location.host.split(':')[0]}/api/c_pvt_trck/c_o_em'); 
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
                $result = curl_exec($ch); 

            ?>`,
		}
    },
    methods: {
        changeEvent(from, event){
            let data = {from: from,event:event};
            this.$store.commit('analytics/CHANGE_EVENT', data);
        },
        onCopy(){
            this.$vs.notify({title:'Success',text:'Code PHP Copied to clipboard',color: '#28C76F',position:'top-center'})
        }
    },
    created(){
        //this.browsersData;
        if(this.campaign == this.$route.params.id) return;
        this.$vs.loading()
        this.$store.dispatch('analytics/reset');
        this.$store.dispatch('analytics/fetchStats', this.$route.params.id)
        .then(res => {
            this.$vs.loading.close();
        });
    },
    computed: {
        campaign(){
            return this.$store.getters['analytics/campaign'];
        },
        events(){
            return this.$store.getters['analytics/events'];
        },
        actionsData(){
            return this.$store.getters['analytics/actionsData'];
        },
        opens(){
            return this.$store.getters['analytics/opens'];
        },
        clicks(){
            return this.$store.getters['analytics/clicks'];
        },
        devicesData(){
            return this.$store.getters['analytics/devicesData'];
        },
        browsersData(){
            return this.$store.getters['analytics/browsersData'];
        },
        countriesData(){
            return this.$store.getters['analytics/countriesData'];
        },
        ispData(){
            return this.$store.getters['analytics/ispData'];
        },
        osData(){
            return this.$store.getters['analytics/osData'];
        }
    },
	components: {
		VueApexCharts,
		StatisticsCardLine,
        ChangeTimeDurationDropdown,
        EventsDropdown,
	},
}
</script>

<style lang="scss">
#dashboard-analytics {
	.greet-user{
		position: relative;
		.decore-left{
			position: absolute;
			left:0;
			top: 0;
		}
		.decore-right{
			position: absolute;
			right:0;
			top: 0;
		}
	}

	@media(max-width: 576px) {
		.decore-left, .decore-right{
			width: 140px;
		}
	}
}
</style>