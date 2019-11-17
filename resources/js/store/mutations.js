import { saveAs } from 'file-saver'

const mutations = {

	UPDATE_SIDEBAR_WIDTH(state, width) {
		state.sidebarWidth = width;
	},
	UPDATE_SIDEBAR_ITEMS_MIN(state, val) {
		state.sidebarItemsMin = val;
	},
	TOGGLE_REDUCE_BUTTON(state, val) {
		state.reduceButton = val;
	},
	TOGGLE_CONTENT_OVERLAY(state, val) {
		state.bodyOverlay = val;
	},
	TOGGLE_IS_SIDEBAR_ACTIVE(state, value) {
		state.isSidebarActive = value;
	},
	UPDATE_THEME(state, val) {
		state.theme = val;
	},
	UPDATE_WINDOW_BREAKPOINT(state, val) {
		state.breakpoint = val;
	},
	UPDATE_PRIMARY_COLOR(state, val) {
		state.themePrimaryColor = val;	
	},
	UPDATE_PERCENTAGE(state, val){
		state.uploadPercent = val;
	},
	UPDATE_EXPPERCENTAGE(state, val){
		state.exportPercent = val;
	},
	UPDATE_CAMPAIGNS(state, payload){
		state.campaigns = payload;
	},
	UPDATE_COUNTRIES(state, payload){
		state.countries = payload;
	},
	UPDATE_FILES(state, payload){
		state.files = payload;
	},
	ADD_COUNTRY(state, country){
		state.countries.push(country);
	},
	UPDATE_VISITORS(state, payload){
		state.visitors = payload;
	},
	ADD_CAMPAIGN(state, campaign){
		let country = state.countries.filter(f => f.id == campaign.country_id);
		campaign['country'] = country[0];
		state.campaigns.push(campaign);
	},
	ADD_FILE(state, file){
		state.files.push(file);
	},
	UPDATE_SETTINGS(state, payload){
		state.settings.token = payload.token;
	},
	login(state){
		state.auth_error = null;
	},
	loginSuccess(state, payload){
		state.auth_error = null;
		state.isLoggedIn = true;
		//console.log(payload.access_token);
		state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
		localStorage.setItem("user", JSON.stringify(state.currentUser));
		axios.defaults.headers.common["Authorization"] = `Bearer ${state.currentUser.token}`
	},
	loginFailed(state, payload){
		state.auth_error = payload;
	},
	logout(state) {
		localStorage.removeItem("user");
		state.isLoggedIn = false;
		state.currentUser = null;
	},
	DELETE_CAMPAIGN(state, id) {
		state.campaigns = state.campaigns.filter(c => c.id != id);
	},
	DELETE_FILE(state, id) {
		state.files = state.files.filter(f => f.id != id);
	},
	DELETE_COUNTRY(state, id) {
		state.countries = state.countries.filter(f => f.id != id);
	},
	EXPORT_CSV(state, payload){
		let data = payload.result
		let csvContent = "";
		csvContent += [
			Object.keys(data[0]).join(";"),
			...data.map(item => Object.values(item).join(";"))
		]
		.join("\n")
		.replace(/(^\[)|(\]$)/gm, "");

		var blob = new Blob([csvContent], {type: "text/csv;charset=utf-8"});
		saveAs(blob, `export_${payload.type}_${data.length}.csv`);
	},
	UPDATE_FILE_EMAILS(state, payload){
		state.files.map(f => {
			if(f.id == payload.file){
				f.emails = payload.emails
				f.state = payload.state;
			}
			return f;
		})
	},
	UPDATE_CAMPAIGN(state, payload){
		state.campaigns.map(c => {
			if(c.id == payload.campaign){
				
			}
			return c;
		})
	}

}

export default mutations