<template>
	<div id="settings">
		<div class="vx-row">
			<div class="vx-col w-full">
				<vx-card title="Settings">
					<div class="mt-4">

						<div  class="vx-row">

							<div class="vx-col sm:w-2/3 w-full mb-2">
								<vs-input class="w-full" label-placeholder="Proxy Token" v-model="settings.token" />
							</div>
							<div class="vx-col sm:w-1/4 w-full mt-4">
								<vs-button @click="saveSettings('token')" >Save Token</vs-button>
							</div>
						</div>
					</div>
				</vx-card>
			</div>
		</div>
		
	</div>
</template>

<script>
export default{
	data() {
		return {
            settings: {
                token: null
            }
		}
    },
    created(){
        if(this.token) return;
        else {
			this.$vs.loading();
			this.$store.dispatch('fetchSettings')
			.then(res => {
                this.settings.token = this.token;
				this.$vs.loading.close();
			})
		}
    },
    mounted(){
        this.settings.token = this.token;
    },
    methods: {
       saveSettings(option){
           if(option == 'token'){
			   if(this.settings.token)
			    {
					this.$store.dispatch('saveToken', this.settings.token)
			   		this.$vs.notify({title:'Success',text:'Token saved successfully',color: '#28C76F',position:'top-center'})
			    }
           }
       }
    },
    computed: {
        token(){
            return this.$store.getters.token;
        }
    }
}
</script>