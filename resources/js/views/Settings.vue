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

						<div v-show="settings.spassword == false" class="vx-row">

							<div class="vx-col sm:w-1/4 w-full mt-4">
								<vs-button @click="settings.spassword = !settings.spassword" >Change Password</vs-button>
							</div>
							
						</div>

						<div v-show="settings.spassword == true" class="vx-row">

							<div class="vx-col sm:w-1/4 w-full mb-2">
								<vs-input class="w-full" label-placeholder="Old Password" v-model="settings.opassword" />
							</div>
							<div class="vx-col sm:w-1/4 w-full mb-2">
								<vs-input class="w-full" label-placeholder="New Password" v-model="settings.password" />
							</div>
							<div class="vx-col sm:w-1/4 w-full mb-2">
								<vs-input class="w-full" label-placeholder="Confirm new Password" v-model="settings.cpassword" />
							</div>
							<div class="vx-col sm:w-1/4 w-full mt-4">
								<vs-button @click="changePassword" >Save Password</vs-button>
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
				token: null,
				password: null,
				cpassword: null,
				opassword: null,
				spassword: false
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
	   },
	   changePassword()
	   {
		   if(this.settings.password != null && this.settings.password != ''){
                if(this.settings.password == this.settings.cpassword){
                    if(this.settings.opassword == null || this.settings.opassword == ''){
                    	this.$vs.notify({title:'Error',text:'Please complete the form',color: '#EA5455',position:'top-center'})
                        return;
					}
					else
					{
						this.$store.dispatch('changePassword', {old: this.settings.opassword, new: this.settings.password})
						.then(res => {
							if (res == '1')
							{
								this.$vs.notify({title:'Success',text:'Password changed successfully',color: '#28C76F',position:'top-center'})
								this.settings.password = null
								this.settings.opassword = null
								this.settings.cpassword = null
								this.settings.spassword = false
							}
							else
								this.$vs.notify({title:'Error',text:'Password you entered is incorrect',color: '#EA5455',position:'top-center'})
						})
					}
                }else{
                    this.$vs.notify({title:'Error',text:'Password confirmation is incorrect',color: '#EA5455',position:'top-center'})
                    return;
                }
			}
			else
			{
				this.$vs.notify({title:'Error',text:'Please complete the form',color: '#EA5455',position:'top-center'})
				return;
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