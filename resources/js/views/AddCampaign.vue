<template>
    <div id="campaigns">
		<div class="vx-row">
			<div class="vx-col md:w-1/2 w-full mb-base">
				<vx-card title="Create New Campaign">
                    <div class="vx-row mb-6">
                        <div class="vx-col w-full">
                            <vs-input class="w-full" label="Campaign Name" v-model="campaign.name" />
                        </div>
                    </div>
                    <div class="vx-row mb-6">
                        <div class="vx-col w-full">
                            <vs-select autocomplete label="Campaign Country" class="w-full" v-model="campaign.country">
                                <vs-select-item :key="index" :value="item.id" :text="item.name" v-for="(item,index) in countries" />
                            </vs-select>
                        </div>
                    </div>
                    <div class="vx-row mb-6">
                        <div class="vx-col w-full">
                            <multiselect
                                v-model="campaign.files"
                                :multiple="true"
                                :close-on-select="true"
                                placeholder="Pick Files"
                                :options="files"
                                label="name"
                                track-by="name">
                            </multiselect>
                        </div>
                    </div>
                    
                    <div class="vx-row">
                        <div class="vx-col w-full">
                            <vs-button @click="addCampaign()" class="mr-3 mb-2">Submit</vs-button>
                        </div>
                    </div>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
    components: { Multiselect },
    data() {
        return {
            campaign: {
                name: '',
                country: '',
                files: '',
            },
        }
    },
    created(){
        if(this.countries.length == 0) this.$store.dispatch('fetchCountries');
        if(this.files.length == 0) this.$store.dispatch('fetchFiles');
    },
    computed: {
        countries(){
            return this.$store.getters.countries;
        },
        files(){
            return this.$store.getters.files;
        }
    },
    methods: {
        addCampaign() {
            this.$store.dispatch('addCampaign', this.campaign)
            .then((res) => {
                this.$vs.notify({title:'Success',text:'Campaign added successfully',color: '#28C76F',position:'top-center'})
                this.$router.push('/campaigns');
            })
            .catch((err) => {
                this.$vs.notify({title:'Error',text:'Please Fill the form',color: '#EA5455',position:'top-center'})
            });
        }
    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>