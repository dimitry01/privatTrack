const axios = require('axios');

const actions = {

	updateSidebarWidth({ commit }, width) {
		commit('UPDATE_SIDEBAR_WIDTH', width);
	},
	updateI18nLocale({ commit }, locale) {
		commit('UPDATE_I18N_LOCALE', locale);
	},
	toggleContentOverlay({ commit }) {
		commit('TOGGLE_CONTENT_OVERLAY');
	},
	updateTheme({ commit }, val) {
		commit('UPDATE_THEME', val);
	},
	register({commit}, data){
		return new Promise((res,rej)=> {
			axios.post('/api/create', data).then((response) => {
				res(response.data);
			});
		})
	},
	checkUser({commit}){
		return new Promise((res,rej)=> {
			axios.get('/api/check').then((response) => {
				res(response.data);
			});
		})
	},
	uploadFile({ commit }, file){
		commit('UPDATE_PERCENTAGE', 0);
		var formData = new FormData();
		formData.append("file", file.file);
		formData.append("description", file.description);
		axios.post('/api/files/add', formData, 
			{	
				headers: {'Content-Type': 'multipart/form-data'},
				onUploadProgress: function( progressEvent ) {
					let uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
					commit('UPDATE_PERCENTAGE', uploadPercentage);
				}.bind(this)
			}
		).then(function(res){
			commit('UPDATE_PERCENTAGE', null);
			commit('ADD_FILE', res.data.file);
		})
		.catch(function(){
			commit('UPDATE_PERCENTAGE', null);
		});
	},
	addCampaign({commit}, campaign){
		return new Promise((resolve, rej) => {
			axios.post('/api/campaigns/add', {campaign: campaign} )
			.then((res) => {
				commit('ADD_CAMPAIGN', res.data.campaign);
				resolve();
			})
			.catch((err) => {
				rej("Error");
			})
		});
	},
	addCountry({commit}, country){
		return new Promise((resolve, rej) => {
			axios.post('/api/countries/add', {country: country} )
			.then((res) => {
				commit('ADD_COUNTRY', res.data.country);
				resolve(res);
			})
			.catch((err) => {
				rej("Error");
			})
		});
	},
	fetchCampaigns({commit}){
		return new Promise((resolve,reject) => {
			axios.get('/api/campaigns')
			.then((res) => {
				commit('UPDATE_CAMPAIGNS', res.data.campaigns);
				resolve(res);
			})
		})
	},
	fetchCountries({commit}){
		return new Promise((resolve,reject) => {
			axios.get('/api/countries')
			.then((res) => {
				commit('UPDATE_COUNTRIES', res.data.countries);
				resolve(res);
			})
		})
	},
	fetchFiles({commit}){
		return new Promise((resolve,reject) => {
			axios.get('/api/files')
			.then((res) => {
				commit('UPDATE_FILES', res.data.files);
				resolve(res);
			})
		})
	},
	fetchVisitors({commit}, params){
		axios.get(`/api/visitors/${params.view}/${params.id}`)
		.then((res) => {
			commit('UPDATE_VISITORS', res.data.visitors);
		});
	},
	fetchSettings({commit}){
		return new Promise((resolve,reject) => {
			axios.get('/api/settings')
			.then((res) => {
				commit('UPDATE_SETTINGS', res.data);
				resolve(res);
			})
		})
	},
	saveToken({commit}, token){
		axios.post('/api/settings/token', {token: token})
	},
	login(context){
		context.commit("login");
	},
	deleteCampaign({commit}, id){
		axios.get(`/api/campaigns/delete/${id}`)
		.then((res) => {
			commit('DELETE_CAMPAIGN', id);
		})
	},
	deleteFile({commit}, id){
		axios.get(`/api/files/delete/${id}`)
		.then((res) => {
			commit('DELETE_FILE', id);
		})
	},
	deleteCountry({commit}, id){
		return new Promise((resolve,reject) => {
			axios.get(`/api/countries/delete/${id}`)
			.then((res) => {
				commit('DELETE_COUNTRY', id);
				resolve(res);
			})
		});
	},
	exportOpens({commit}, id){
		return new Promise((resolve,reject) => {
			commit('UPDATE_EXPPERCENTAGE', 1)
			axios.get(`/api/campaigns/export/opens/${id}`)
			.then((res) => {
				console.log(res.data)
				commit('UPDATE_EXPPERCENTAGE', 0)
				if (res.data.result.length)
				{
					commit('EXPORT_CSV', res.data)
					resolve('1')
				}
				else
					resolve('0')
			})
		})
	},
	exportClicks({commit}, id){
		return new Promise((resolve,reject) => {
			commit('UPDATE_EXPPERCENTAGE', 1)
			axios.get(`/api/campaigns/export/clicks/${id}`)
			.then((res) => {
				console.log(res.data)
				commit('UPDATE_EXPPERCENTAGE', 0)
				if (res.data.result.length)
				{
					commit('EXPORT_CSV', res.data)
					resolve('1')
				}
				else
					resolve('0')
			})
		})
	},
	exportVisitors({commit}, id){
		return new Promise((resolve,reject) => {
			commit('UPDATE_EXPPERCENTAGE', 1)
			axios.get(`/api/files/export/${id}`)
			.then((res) => {
				console.log(res.data)
				commit('UPDATE_EXPPERCENTAGE', 0)
				if (res.data.result.length)
				{
					commit('EXPORT_CSV', res.data)
					resolve('1')
				}
				else
					resolve('0')
			})
		})
	},
	exportNoOpen({commit}, id){
		return new Promise((resolve,reject) => {
			commit('UPDATE_EXPPERCENTAGE', 1)
			axios.get(`/api/campaigns/export/noopens/${id}`)
			.then((res) => {
				console.log(res.data)
				commit('UPDATE_EXPPERCENTAGE', 0)
				if (res.data.result.length)
				{
					commit('EXPORT_CSV', res.data)
					resolve('1')
				}
				else
					resolve('0')
			})
		})
	},
	exportAudience({commit}, data){
		return new Promise((resolve,reject) => {
			commit('UPDATE_EXPPERCENTAGE', 1)
			axios.post(`/api/campaigns/export/audience`, {campaign: data.campaign.id, action: data.action, country: data.country.name})
			.then((res) => {
				commit('UPDATE_EXPPERCENTAGE', 0)
				console.log(res.data)
				if (res.data.result.length)
				{
					commit('EXPORT_CSV', res.data)
					resolve('1')
				}
				else
					resolve('0')
			})
		})
	},
	refreshFile({commit}, id){
		new Promise((resolve,reject) => {
			axios.get(`/api/files/emails/${id}`)
			.then((res) => {
				commit('UPDATE_FILE_EMAILS', res.data)
				resolve();
			})
		})
	},
	addCampaignFile({commit}, data){
		new Promise((resolve,reject) => {
			axios.post('/api/campaigns/files/add', data)
			.then((res) => {
				//commit('UPDATE_CAMPAIGN', res.data)
				resolve(res.data.message);
			})
		})
	}
}

export default actions