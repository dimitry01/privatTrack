export default {
    namespaced: true,
    state: {
        campaign: '',
        visitors: [],
        openers: [],
        clickers: [],
        countries: [],
        isps: [],
        os: [],
        refers: [],
        events: {
            device: 'opens',
            browser: 'opens',
        }
    },
    getters: {
        campaign: state => state.campaign,
        events: state => state.events,
        clicks: state => state.clickers.length,
        opens: state =>  state.openers.length,
        visitorsCount: state => state.visitors,
        actionsData(state){
            if(state.openers.length > 0){
                let analyticsData = [
                    { 'actionType': 'Opens', 'counts': state.openers.length, color: 'primary' },
                    { 'actionType': 'Clicks', 'counts': state.clickers.length, color: 'warning' },
                ]
                let total = state.visitors;
                let cp = parseInt((state.clickers.length/state.openers.length)*100);
                let op = parseInt((state.openers.length/total)*100);

                return {series: [op,cp], analyticsData: analyticsData};
            }
            return {series: [0,0], analyticsData: []};
        },
        devicesData(state){
            if(state.openers.length > 0){
            let selected = [];
                if(state.events.device == 'opens'){
                    selected = state.openers;
                }else if(state.events.device == 'clicks'){
                    selected = state.clickers;
                }
                let md = selected.reduce((arr, v) => v.device == "mobile" ? [...arr,v] : arr ,[])
                let dd = selected.reduce((arr, v) => v.device == "desktop" ? [...arr,v] : arr ,[])
                let td = selected.reduce((arr, v) => v.device == "tablet" ? [...arr,v] : arr ,[])
                let od = selected.reduce((arr, v) => (v.device == null || v.device == 'others') ? [...arr,v] : arr ,[])

                let mp = (md.length/selected.length) * 100;
                let dp = (dd.length/selected.length) * 100;
                let tp = (td.length/selected.length) * 100;
                let op = (od.length/selected.length) * 100;
                
                if(isNaN(mp)) mp = 0 
                if(isNaN(dp)) dp = 0 
                if(isNaN(tp)) tp = 0 
                if(isNaN(op)) op = 0 

                let analyticsData = [
                    { device: 'Dekstop', icon: 'MonitorIcon', color: 'primary', sessionsPercentage: parseFloat(dp).toFixed(1) },
                    { device: 'Mobile', icon: 'SmartphoneIcon', color: 'warning', sessionsPercentage: parseFloat(mp).toFixed(1) },
                    { device: 'Tablet', icon: 'TabletIcon', color: 'danger', sessionsPercentage: parseFloat(tp).toFixed(1) },
                    { device: 'Others', icon: 'MoreHorizontalIcon', color: 'secondary', sessionsPercentage: parseFloat(op).toFixed(1) },
                ]
                let series = [dp,mp,tp,op];
                return {series: series, analyticsData: analyticsData};
            }
            return {series: [0,0,0], analyticsData: []}
        },
        browsersData(state){
            if(state.openers.length > 0){
                let key = "browser";
                let result = {};
                let selected = [];
                if(state.events.browser == 'opens'){
                    selected = state.openers;
                }else if(state.events.browser == 'clicks'){
                    selected = state.clickers;
                }
                selected.forEach(item => {
                    if (!result[item[key]]){
                        result[item[key]] = []
                    }
                    result[item[key]].push(item)
                });
                let browsers = [];
                for(let browser in result){
                    let ratio = parseInt((result[browser].length/selected.length) * 100)
                    let data = {name: browser, ratio: ratio}
                    browsers.push(data);
                }
                browsers = browsers.sort((b1, b2) => b2.ratio - b1.ratio);
                return browsers;
            }
        },
        countriesData(state){
            return state.countries;
        },
        ispData(state){
            return state.isps;
        },
        osData(state){
            return state.os;
        },
        refersData(state){
            return state.refers;
        }
    },
    mutations: {
        UPDATE_STATS(state, payload){
            state.countries = payload.countries;
            state.isps = payload.isp;
            state.os = payload.os;
            state.clickers = payload.clickers;
            state.openers = payload.openers;
            state.visitors = payload.visitors;
        },
        CHANGE_EVENT(state, data){
            if(data.from == 'device'){
                state.events.device = data.event;
            }else if(data.from == 'browser'){
                state.events.browser = data.event;
            }
        },
        UPDATE_CAMPAIGN(state, id){
            state.campaign = id
        },
        RESET_STATS(state){
            state.countries = [];
            state.isps = [];
            state.os = [];
            state.clickers = [];
            state.openers = [];
            state.visitors = [];
        }
    },
    actions: {
        fetchStats({commit}, id){
            return new Promise((resolve,reject) => {
                axios.get(`/api/stats/${id}`)
                .then((res) => {
                    commit('UPDATE_CAMPAIGN', id);
                    commit('UPDATE_STATS', res.data);
                    resolve(res);
                })
            })
        },
        reset({commit}){
            commit('RESET_STATS');
        }
    }
}