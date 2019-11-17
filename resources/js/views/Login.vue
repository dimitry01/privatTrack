<template>
	<div class="h-screen flex w-full bg-img">
		<div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 mx-auto self-center">
			<vx-card>
				<div slot="no-body" class="full-page-bg-color">
					<div class="vx-row">
						<div class="vx-col hidden sm:hidden md:hidden lg:block lg:w-1/2 mx-auto self-center">
							<img src="../assets/images/pages/login.png" alt="login" class="mx-auto">							
						</div>
						<div class="vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center bg-white">
							<div class="p-8">
								<div class="vx-card__title mb-8">
									<h4 class="mb-4">Login</h4>
									<p>Welcome back, please login to your account.</p>
								</div>
								<vs-input icon="icon icon-user" icon-pack="feather" label-placeholder="Username" v-model="form.email" class="w-full mb-6 no-icon-border"/>
								<vs-input type="password" icon="icon icon-lock" icon-pack="feather" label-placeholder="Password" v-model="form.password" class="w-full mb-4 no-icon-border" />
								<vs-button @click="authenticate()" >Login</vs-button>

							</div>
						</div>
					</div>
				</div>
			</vx-card>
		</div>
	</div>
</template>

<script>
import {login} from '../auth';
export default {
    name: "login",
    data(){
        return {
            form: {
                email: '',
                password: ''
            }
        }
    },
    created(){
        this.$vs.loading();
        this.$store.dispatch('checkUser')
        .then(res => {
            this.$vs.loading.close();
            if (!res[0].length)
                this.$router.push({path: '/register'});
        })
    },
    methods: {
        authenticate(){
            this.$store.dispatch('login');
            
            login(this.$data.form)
            .then((res) => {
                this.$store.commit('loginSuccess', res);
                this.$router.push({path: '/campaigns'});
            })
            .catch((err) => {
                this.$vs.notify({title:'Error',text:'Email or password incorrect',color: '#EA5455',position:'top-center'})
            });
        }
    },
    computed: {
        authError(){
            return this.$store.getters.authError;
        }
    }
    

}
</script>


<style scoped>
.error {
    border-color: red !important;
}
.form{
    margin-top: 160px;
}
.btn-login {
    background-image: linear-gradient(-31deg, #2356d9, #3a70fa);
    color: #fff;
    border-color: #2c61e7;
    padding: 15px 30px !important;
    font: 700 20px/1 Gilroy-Bold, sans-serif !important;
}
</style>