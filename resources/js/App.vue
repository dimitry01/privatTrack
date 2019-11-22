<template>
	<div id="app">
		<transition
          name="fade"
          mode="out-in">
        <router-view></router-view>
      </transition>
	</div>
</template>

<script>
import themeConfig from './themeConfig.js'

export default {
    watch: {
        '$store.state.theme'(val) {
            this.toggleClassInBody(val);
        }
    },
    computed:{
        currentUser(){
            return this.$store.getters.currentUser;
        }
    },
    methods: {
        toggleClassInBody(className) {
            if (className == 'dark') {
                if (document.body.className.match('theme-semi-dark')) document.body.classList.remove('theme-semi-dark');
                document.body.classList.add('theme-dark');
            } else if (className == 'semi-dark') {
                if (document.body.className.match('theme-dark')) document.body.classList.remove('theme-dark');
                document.body.classList.add('theme-semi-dark');
            } else {
                if (document.body.className.match('theme-dark')) document.body.classList.remove('theme-dark');
                if (document.body.className.match('theme-semi-dark')) document.body.classList.remove('theme-semi-dark');
            }
        },
    },
    mounted() {
        this.toggleClassInBody(themeConfig.theme)
    },
    created(){
        if(!this.currentUser && this.$route.path != '/5cd2pvt2020' && this.$route.path != '/5cd1pvt2020'){
            this.$router.push('/home');
        }
    }
}
</script>