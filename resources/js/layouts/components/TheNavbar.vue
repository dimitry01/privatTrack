<template>
<div class="relative">
	<div class="vx-navbar-wrapper">
		<vs-navbar class="vx-navbar navbar-custom" :color="navbarColor" :class="classObj">

			<!-- SM - OPEN SIDEBAR BUTTON -->
			<feather-icon class="sm:inline-flex xl:hidden cursor-pointer mr-1" icon="MenuIcon" @click.stop="showSidebar"></feather-icon>
            <vs-row>
                <vs-col vs-w="4" v-if="uploadPercent != null">
                    <span>Uploading File {{uploadPercent}}%</span>
                    <vs-progress :percent="uploadPercent" color="primary"></vs-progress>
                </vs-col>
                <vs-col vs-w="4" v-if="exportPercent == 1">
                    <span>Exporting...</span>
                </vs-col>
            </vs-row>
			<vs-spacer></vs-spacer>

			<!-- USER META -->
			<div class="the-navbar__user-meta flex items-center sm:ml-5 ml-2">
				<div class="text-right leading-tight hidden sm:block">
                    <p class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="logout"><feather-icon icon="LogOutIcon" svgClasses="w-4 h-4"></feather-icon> <span class="ml-2">Logout</span></p>
				</div>
			</div>

		</vs-navbar>
	</div>
</div>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'


export default {
    name: "the-navbar",
    props: {
        navbarColor: {
            type: String,
            default: "#fff",
        },
    },
    data() {
        return {
            searchQuery: '',
            showFullSearch: false,
            unreadNotifications: [
                { index: 0, title: 'New Message', msg: 'Are your going to meet me tonight?', icon: 'MessageSquareIcon', time: 'Wed Jan 30 2019 07:45:23 GMT+0000 (GMT)', category: 'primary' },
                { index: 1, title: 'New Order Recieved', msg: 'You got new order of goods.', icon: 'PackageIcon', time: 'Wed Jan 30 2019 07:45:23 GMT+0000 (GMT)', category: 'success' },
                { index: 2, title: 'Server Limit Reached!', msg: 'Server have 99% CPU usage.', icon: 'AlertOctagonIcon', time: 'Thu Jan 31 2019 07:45:23 GMT+0000 (GMT)', category: 'danger' },
                { index: 3, title: 'New Mail From Peter', msg: 'Cake sesame snaps cupcake', icon: 'MailIcon', time: 'Fri Feb 01 2019 07:45:23 GMT+0000 (GMT)', category: 'primary' },
                { index: 4, title: 'Bruce\'s Party', msg: 'Chocolate cake oat cake tiramisu marzipan', icon: 'CalendarIcon', time: 'Fri Feb 02 2019 07:45:23 GMT+0000 (GMT)', category: 'warning' },
            ],
            settings: { // perfectscrollbar settings
                maxScrollbarLength: 60,
                wheelSpeed: .60,
            },
            autoFocusSearch: false,
            showBookmarkPagesDropdown: false,
        }
    },
    watch: {
        '$route'() {
            if (this.showBookmarkPagesDropdown) this.showBookmarkPagesDropdown = false
        },
    },
    computed: {
        activeUserInfo() {
            return this.$store.state.AppActiveUser;
        },
        sidebarWidth() {
            return this.$store.state.sidebarWidth;
        },
        breakpoint() {
            return this.$store.state.breakpoint;
        },
        classObj() {
            if (this.sidebarWidth == "default") return "navbar-default"
            else if (this.sidebarWidth == "reduced") return "navbar-reduced"
            else if (this.sidebarWidth) return "navbar-full"
        },
        getCurrentLocaleData() {
            const locale = this.$i18n.locale;
            if (locale == "en") return { flag: "us", lang: 'English' }
            else if (locale == "pt") return { flag: "br", lang: 'Portuguese' }
            else if (locale == "fr") return { flag: "fr", lang: 'French' }
            else if (locale == "de") return { flag: "de", lang: 'German' }
        },
        starredPages() {
            return this.$store.getters.starredPages;
        },
        starredPagesLimited() { return this.starredPages.slice(0, 10); },
        starredPagesMore() { return this.starredPages.slice(10); },
        uploadPercent() {
            return this.$store.getters.uploadPercent;
        },
        exportPercent() {
            return this.$store.getters.exportPercent;
        }
    },
    methods: {
        logout(){
            this.$store.commit('logout');
            this.$router.push('/login');
        },
        updateLocale(locale) {
            this.$i18n.locale = locale;
        },
        showSidebar() {
            this.$store.commit('TOGGLE_IS_SIDEBAR_ACTIVE', true);
        },
        selected(item) {
            this.$router.push(item.url)
            this.showFullSearch = false;
        },
        actionClicked(item) {
            // e.stopPropogation();
            this.$store.dispatch('updateStarredPage', { index: item.index, val: !item.highlightAction });
        },
        showNavbarSearch() {
            this.showFullSearch = true;
        },
        showSearchbar() {
            this.showFullSearch = true;
        },
        elapsedTime(startTime) {
            let x = new Date(startTime);
            let now = new Date();
            var timeDiff = now - x;
            timeDiff /= 1000;

            var seconds = Math.round(timeDiff);
            timeDiff = Math.floor(timeDiff / 60);

            var minutes = Math.round(timeDiff % 60);
            timeDiff = Math.floor(timeDiff / 60);

            var hours = Math.round(timeDiff % 24);
            timeDiff = Math.floor(timeDiff / 24);

            var days = Math.round(timeDiff % 365);
            timeDiff = Math.floor(timeDiff / 365);

            var years = timeDiff;

            if (years > 0) {
                return years + (years > 1 ? ' Years ' : ' Year ') + 'ago';
            } else if (days > 0) {
                return days + (days > 1 ? ' Days ' : ' Day ') + 'ago';
            } else if (hours > 0) {
                return hours + (hours > 1 ? ' Hrs ' : ' Hour ') + 'ago';
            } else if (minutes > 0) {
                return minutes + (minutes > 1 ? ' Mins ' : ' Min ') + 'ago';
            } else if (seconds > 0) {
                return seconds + (seconds > 1 ? `${seconds} sec ago` : 'just now');
            }

            return 'Just Now'
        },
        test() {
            alert();
        },
        outside: function() {
            this.showBookmarkPagesDropdown = false
        },
    },
    directives: {
        'click-outside': {
            bind: function(el, binding) {
                const bubble = binding.modifiers.bubble
                const handler = (e) => {
                    if (bubble || (!el.contains(e.target) && el !== e.target)) {
                        binding.value(e)
                    }
                }
                el.__vueClickOutside__ = handler
                document.addEventListener('click', handler)
            },

            unbind: function(el) {
                document.removeEventListener('click', el.__vueClickOutside__)
                el.__vueClickOutside__ = null

            }
        }
    },
    components: {
        VuePerfectScrollbar
    },
}
</script>

<style scoped>
.fill-row-loading{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

</style>
