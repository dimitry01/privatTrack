<template>
	<div id="campaigns">
		<div class="vx-row">
			<div class="vx-col w-full">
				<vx-card title="Campaigns">
					<div class="mt-4">

						<div v-show="activePrompt" class="vx-row">
							<div class="vx-col sm:w-1/4 w-full" style="z-index: 1050;">
								<multiselect
									v-model="edit.file"
									:multiple="false"
									:close-on-select="true"
									placeholder="Pick A File"
									:options="files"
									label="name"
									track-by="name">
								</multiselect>
							</div>
							
							<div class="vx-col sm:w-1/4 w-full">
								<vs-button @click="addFile()" >Add File</vs-button>
							</div>
						</div>

						<div v-show="audiencePrompt" class="vx-row">
							<div class="vx-col sm:w-1/4 w-full mt-5" style="z-index: 1050;">
								<multiselect
									v-model="audience.country"
									:multiple="false"
									:close-on-select="true"
									placeholder="Pick A Country"
									:options="countries"
									label="name"
									track-by="name">
								</multiselect>
							</div>
							<div class="vx-col sm:w-1/4 w-full" style="z-index: 1050;">
								<vs-input class="w-full" label="Campaign Name" disabled v-model="audience.campaign.name" />
							</div>
							<div class="vx-col sm:w-1/4 w-full" style="z-index: 1050;">
								<vs-select autocomplete label="Select Action" class="w-full" v-model="audience.action">
									<vs-select-item value="open" text="Openers" />
									<vs-select-item value="click" text="Clickers" />
								</vs-select>
							</div>
							<div class="vx-col sm:w-1/4 w-full mt-5">
								<vs-button @click="exportAudience()" >Export Data</vs-button>
							</div>
						</div>

                        <div slot="fc-header-right" class="flex justify-end">
                            <vs-button icon-pack="feather" icon="icon-plus" to="/campaigns/add" >New Campaign</vs-button>
                        </div>
						<vs-table :data="campaigns" pagination max-items="10" search>
							<template slot="thead">
								<vs-th sort-key="id">Campaign ID.</vs-th>
								<vs-th sort-key="name">NAME</vs-th>
                                <vs-th sort-key="country.name">COUNTRY</vs-th>
								<vs-th sort-key="clicks">CLICKS</vs-th>
								<vs-th sort-key="opens">OPENS</vs-th>
								<vs-th sort-key="emails">EMAILS</vs-th>
								<vs-th sort-key="created_at">START DATE</vs-th>
							</template>
							
							<template slot-scope="{data}" >
								<vs-tr :key="indextr" v-for="(tr, indextr) in data">
									<vs-td :data="tr.id">
										<span>#{{tr.id}}</span>
									</vs-td>
									<vs-td :data="tr.name">
										<span>{{tr.name}}</span>
									</vs-td>
                                    <vs-td :data="tr.country.name">
										<span>{{tr.country.name}}</span>
									</vs-td>
									<vs-td :data="tr.clicks">
										<span v-if="tr.clicks">{{tr.clicks}}</span>
										<span v-else>0</span>
									</vs-td>
									<vs-td :data="tr.opens">
										<span v-if="tr.opens">{{tr.opens}}</span>
										<span v-else>0</span>
									</vs-td>
									<vs-td :data="tr.emails">
										<span v-if="tr.emails">{{tr.emails}}</span>
										<span v-else>0</span>
									</vs-td>
                                    <vs-td :data="tr.created_at">
										<span>{{tr.created_at}}</span>
									</vs-td>
									<vs-td>
										<vs-button class="inline" color="primary" type="gradient" :to="'/campaigns/analytics/'+tr.id" >Analytics</vs-button>
										<vs-dropdown vs-trigger-click class="btnx"> 
											<vs-button class="btn-drop" type="gradient" icon="expand_more"></vs-button>
											<vs-dropdown-menu >
												<vs-dropdown-item @click="exportData('noopen',tr.id)" >
													Export No-Openers 
												</vs-dropdown-item>
												<vs-dropdown-item @click="exportData('open',tr.id)" >
													Export Openers 
												</vs-dropdown-item>
												<vs-dropdown-item  @click="exportData('click',tr.id)" divider>
													Export Clickers
												</vs-dropdown-item>
												<vs-dropdown-item  @click="exportData('audience',tr.id)" divider>
													Export Audience
												</vs-dropdown-item>
												<vs-dropdown-item  @click="editCampaign(tr.id)" divider>
													Add File
												</vs-dropdown-item>
												<vs-dropdown-item  @click="deleteCampaign(tr.id)" divider>
													Delete
												</vs-dropdown-item>
											</vs-dropdown-menu>
										</vs-dropdown>
									</vs-td>
								</vs-tr>
							</template>
						</vs-table>
					</div>
					
				</vx-card>
			</div>
		</div>
		
	</div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import Prism from 'vue-prism-component'

export default{
	data() {
		return {
			loading: null,
			activePrompt: false,
			audiencePrompt: false,
			edit: {
				file: null,
				campaign: null
			},
			audience: {
				campaign: {
					name: '',
				},
				action: '',
				country: ''
			}
		}
	},
	created(){
		if(this.campaigns.length == 0){
			this.$vs.loading();
			this.$store.dispatch('fetchCampaigns')
			.then(res => {
				this.$vs.loading.close();
			})
		}
		if(this.files.length == 0) this.$store.dispatch('fetchFiles');
		if(this.countries.length == 0) this.$store.dispatch('fetchCountries');
	},
    methods: {
		editCampaign(id){
			this.activePrompt = true;
			this.edit.campaign = id;
		},
		addFile(){
			this.$store.dispatch('addCampaignFile', this.edit)
			.then(res => {
				this.activePrompt = false;
				this.edit.file = null;
				this.edit.campaign = null;
				this.$vs.notify({title:'Success',text:'File added successfully',color: '#28C76F',position:'top-center'})
			})
		},
		deleteCampaign(id){
			this.$swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				if (result.value) {
					this.$store.dispatch('deleteCampaign', id);
					this.$vs.notify({title:'Success',text:'Campaign deleted successfully',color: '#28C76F',position:'top-center'})
				}
			})
		},
		exportData(type, id){
			if(type == 'open'){
				this.$store.dispatch('exportOpens', id);
			} else if (type == 'noopen') {
				this.$store.dispatch('exportNoOpen', id);
			} else if (type == 'click') {
				this.$store.dispatch('exportClicks', id);
			} else if (type == 'audience') {
				this.audiencePrompt = true;
				this.audience.campaign = this.campaigns.filter(c => c.id == id)[0];
			}
		},
		exportAudience(){
			if (this.audience.campaign && this.audience.country && this.audience.action){
				this.$store.dispatch('exportAudience', this.audience);
				this.audiencePrompt = false;
			}
			else
			{
				this.$vs.notify({title:'Error',text:'Please complete the form',color: '#FF9F43',position:'top-center'})
			}
		}
	},
	computed: {
		campaigns(){
			let camps = this.$store.getters.campaigns;
			return camps;
		},
		files(){
			return this.$store.getters.files;
		},
		countries(){
			return this.$store.getters.countries;
		}
	},
    components: {
		Prism,
		Multiselect
	}
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

.btnx {
	top: 9px;
}
</style>