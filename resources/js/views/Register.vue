<template>
	<div class="h-screen flex w-full bg-img">
		<div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 mx-auto self-center">
			<vx-card>
				<div slot="no-body" class="full-page-bg-color">
					<div class="vx-row">
						<div class="vx-col hidden sm:hidden md:hidden lg:block lg:w-1/2 mx-auto self-center">
							<img src="../assets/images/pages/register.jpg" alt="login" class="mx-auto">							
						</div>
						<div class="vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center bg-white">
							<div class="p-8">
								<div class="vx-card__title mb-8">
									<h4 class="mb-4">Register</h4>
									<p>Welcome, please create your account.</p>
								</div>
								<vs-input icon="icon icon-user" icon-pack="feather" label-placeholder="Username" v-model="form.email" class="w-full mb-6 no-icon-border"/>
								<vs-input icon="icon icon-lock" icon-pack="feather" label-placeholder="Password, Make sure you remember it well" v-model="form.password" class="w-full mb-4 no-icon-border" />
								<vs-button @click="register()" >Register</vs-button>
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
            },
            error: null
        }
    },
    created(){
        this.$vs.loading();
        this.$store.dispatch('checkUser')
        .then(res => {
            this.$vs.loading.close();
            if (res.length > 0)
                this.$router.push({path: '/login'});
        })
    },
    methods: {
        register()
        {
            this.$store.dispatch('register', this.form)
            .then((res) => {
                this.$router.push({path: '/login'});
            });
        }
    },    

}
</script>


<style scoped>
.error {
    text-align: center;
    color: red;
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