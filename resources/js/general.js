export function initialize(store, router){
    router.beforeEach((to,from,next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.getters.currentUser;
        if(requiresAuth && !currentUser){
            if(this) this.$vs.loading()
            next('/login');
            if(this) this.$vs.loading.close();
        }else if(to.path == '/login' && currentUser ){
            next('/campaigns');
        }else{
            axios.interceptors.response.use(null, (error) => {
                if(error.response.status == 401){
                    store.commit('logout');
                    router.push('/login');
                }
                return Promise.reject(error);
            });
            next();
        }
    });

    if (store.getters.currentUser) {
        setAuthorization(store.state.currentUser.token);
    }
}

export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}