<template>
	<div id="countries">
		<div class="vx-row">
			<div class="vx-col w-full">
				<vx-card title="Countries">
					<div class="mt-4">
						<div v-show="activePrompt" class="vx-row">
							<div class="vx-col sm:w-1/4 w-full mb-2">
								<vs-input name="countryName" label-placeholder="Country Name" v-model="country.name" class="w-full mb-6" />
							</div>

							<div class="vx-col sm:w-1/4 w-full mt-4">
								<vs-button @click="addCountry" >Add Country</vs-button>
							</div>
						</div>
                        <div slot="fc-header-right" class="flex justify-end">
                            <vs-button v-show="!activePrompt" icon-pack="feather" icon="icon-plus" @click="activePrompt = true" >New Country</vs-button>
                        </div>
                
						<vs-table :data="countries" pagination max-items="10" search>
							<template slot="thead">
								<vs-th sort-key="id">Country ID.</vs-th>
								<vs-th sort-key="name">NAME</vs-th>
								<vs-th>ACTIONS</vs-th>
							</template>

							<template slot-scope="{data}">
								<vs-tr :key="indextr" v-for="(tr, indextr) in data">
									<vs-td :data="data[indextr].id">
										<span>#{{data[indextr].id}}</span>
									</vs-td>
									<vs-td :data="data[indextr].name">
										<span>{{data[indextr].name}}</span>
									</vs-td>
									<vs-td :data="data[indextr].id">
										<vs-button color="primary" type="gradient" @click.prevent="deleteCountry(tr.id)">Delete</vs-button>
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

import Prism from 'vue-prism-component'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'

export default{
	name:'countries',
	data() {
		return {
            activePrompt: false,
            settings: {
				maxScrollbarLength: 60,
				wheelSpeed: 0.30,
            },
            country: {
                name: '',
			},
			custom: false,
		}
	},
	created(){
		if(this.country.length > 0) return;
		else {
			this.$vs.loading();
			this.$store.dispatch('fetchCountries')
			.then(res => {
				this.$vs.loading.close();
			})
		}
	},
    methods: {
        clearFields() {
			this.country.name = '';
			this.country.type = '';
			this.country.sponsor = '';
		},
		addCountry() {
			this.$store.dispatch('addCountry', this.country)
			.then(res => {
				this.$vs.notify({title:'Success',text:'Country added successfully',color: '#28C76F',position:'top-center'})
			})
			.catch((err) => {
                this.$vs.notify({title:'Error',text:'Please Fill the form',color: '#EA5455',position:'top-center'})
            });
			this.activePrompt = false;
		},
		deleteCountry(id){
			this.$store.dispatch('deleteCountry', id)
			.then(res => {
				this.$vs.notify({title:'Success',text:'Country deleted successfully',color: '#28C76F',position:'top-center'})
			})
		}
	},
	computed: {
		countries(){
			return this.$store.getters.countries;
		},
		types(){
			if(this.countries.length > 0){
				let td = [];
				for(let t_country of this.countries){
					td.push(t_country.type);
				}
				return td.reduce((arr, type) => arr.includes(type) ? arr : [...arr, type], []);
			}
			return ['None'];
		}
	},
    components: {
        Prism,
        VuePerfectScrollbar
	}
}
</script>

<style lang="scss">

</style>